<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReturnController extends Controller
{
    const DAILY_FINE = 5000; // Rp 5,000 per day

    /**
     * Get all return records
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);

        $perPage = $request->input('per_page', 15);

        $returns = Peminjaman::where('status', 'dikembalikan')
            ->with(['mahasiswa', 'buku', 'denda'])
            ->paginate($perPage);

        return response()->json([
            'count' => $returns->count(),
            'total' => $returns->total(),
            'current_page' => $returns->current_page(),
            'last_page' => $returns->last_page(),
            'returns' => $returns->map(fn($return) => $this->formatReturn($return))->values(),
        ]);
    }

    /**
     * Process book return and auto-calculate fine
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processReturn(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:pinjamans,id',
            'tanggal_kembali' => 'nullable|date|before_or_equal:today',
            'kondisi_buku' => 'nullable|string|in:baik,rusak_ringan,rusak_berat',
        ]);

        try {
            $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'denda'])->find($request->peminjaman_id);

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman tidak ditemukan',
                ], 404);
            }

            if ($peminjaman->status === 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku sudah dikembalikan sebelumnya',
                ], 422);
            }

            // Get return date
            $returnDate = $request->tanggal_kembali ? Carbon::parse($request->tanggal_kembali) : Carbon::now();
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali_rencana);

            // Calculate late days
            $lateDays = 0;
            if ($returnDate->isAfter($dueDate)) {
                $lateDays = $returnDate->diffInDays($dueDate);
            }

            // Calculate fine
            $fineAmount = $lateDays * self::DAILY_FINE;

            // Update peminjaman status
            $peminjaman->update([
                'tanggal_kembali_aktual' => $returnDate,
                'status' => 'dikembalikan',
            ]);

            // Create or update denda
            if ($fineAmount > 0) {
                $denda = Denda::updateOrCreate(
                    ['pinjaman_id' => $peminjaman->id],
                    [
                        'hari_terlambat' => $lateDays,
                        'total_denda' => $fineAmount,
                        'status_bayar' => 'belum',
                    ]
                );
            } else {
                // No fine, but record zero fine
                $denda = Denda::updateOrCreate(
                    ['pinjaman_id' => $peminjaman->id],
                    [
                        'hari_terlambat' => 0,
                        'total_denda' => 0,
                        'status_bayar' => 'terbayar', // Considered paid if no fine
                    ]
                );
            }

            // Increment book stock
            $peminjaman->buku->increment('stok_tersedia');

            // Create history record
            History::create([
                'pinjaman_id' => $peminjaman->id,
                'action' => 'return',
                'notes' => 'Pengembalian buku. Denda: Rp ' . number_format($fineAmount, 0, ',', '.'),
                'created_by' => (string)(Auth::id() ?? 'system'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengembalian buku berhasil diproses',
                'peminjaman_id' => $peminjaman->id,
                'tanggal_kembali_aktual' => $returnDate,
                'hari_terlambat' => $lateDays,
                'denda_amount' => $fineAmount,
                'denda_formatted' => 'Rp ' . number_format($fineAmount, 0, ',', '.'),
                'payment_required' => $fineAmount > 0,
                'denda_id' => $denda->id,
                'return_data' => $this->formatReturn($peminjaman->load(['denda'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pengembalian: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get return record by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $return = Peminjaman::with(['mahasiswa', 'buku', 'denda'])->find($id);

        if (!$return || $return->status !== 'dikembalikan') {
            return response()->json([
                'success' => false,
                'message' => 'Pengembalian tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'return' => $this->formatReturn($return),
        ]);
    }

    /**
     * Confirm fine payment
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirmPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'nullable|string',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            $denda = Denda::find($id);

            if (!$denda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Denda tidak ditemukan',
                ], 404);
            }

            if ($denda->status_bayar === 'terbayar') {
                return response()->json([
                    'success' => false,
                    'message' => 'Denda sudah terbayar',
                ], 422);
            }

            // Update denda status
            $denda->update([
                'status_bayar' => 'terbayar',
                'dibayar_at' => Carbon::now(),
            ]);

            // Create history record
            History::create([
                'pinjaman_id' => $denda->pinjaman_id,
                'action' => 'payment',
                'notes' => 'Pembayaran denda. Metode: ' . ($request->payment_method ?? 'tidak disebutkan'),
                'created_by' => (string)(Auth::id() ?? 'system'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil dicatat',
                'denda' => [
                    'id' => $denda->id,
                    'total_denda' => $denda->total_denda,
                    'total_denda_formatted' => 'Rp ' . number_format($denda->total_denda, 0, ',', '.'),
                    'status_bayar' => $denda->status_bayar,
                    'dibayar_at' => $denda->dibayar_at,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Format return response
     *
     * @param Peminjaman $return
     * @return array
     */
    private function formatReturn($return)
    {
        return [
            'id' => $return->id,
            'mahasiswa' => [
                'id' => $return->mahasiswa->id,
                'nim' => $return->mahasiswa->nim,
                'nama' => $return->mahasiswa->nama,
            ],
            'buku' => [
                'id' => $return->buku->id,
                'nama_buku' => $return->buku->nama_buku,
                'penerbit' => $return->buku->penerbit,
            ],
            'tanggal_pinjam' => $return->tanggal_pinjam,
            'tanggal_kembali_rencana' => $return->tanggal_kembali_rencana,
            'tanggal_kembali_aktual' => $return->tanggal_kembali_aktual,
            'status' => $return->status,
            'denda' => $return->denda ? [
                'id' => $return->denda->id,
                'hari_terlambat' => $return->denda->hari_terlambat,
                'total_denda' => $return->denda->total_denda,
                'total_denda_formatted' => 'Rp ' . number_format($return->denda->total_denda, 0, ',', '.'),
                'status_bayar' => $return->denda->status_bayar,
                'dibayar_at' => $return->denda->dibayar_at,
            ] : null,
        ];
    }
}
