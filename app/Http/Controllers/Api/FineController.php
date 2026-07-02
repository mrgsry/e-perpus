<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FineController extends Controller
{
    const DAILY_FINE = 5000; // Rp 5,000 per day

    /**
     * Get all fines
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'status' => 'nullable|string|in:belum,terbayar',
        ]);

        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');

        $query = Denda::with(['peminjaman.mahasiswa', 'peminjaman.buku']);

        if ($status) {
            $query->where('status_bayar', $status);
        }

        $fines = $query->paginate($perPage);

        return response()->json([
            'count' => $fines->count(),
            'total' => $fines->total(),
            'current_page' => $fines->current_page(),
            'last_page' => $fines->last_page(),
            'fines' => $fines->map(fn($fine) => $this->formatFine($fine))->values(),
        ]);
    }

    /**
     * Get fine by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $fine = Denda::with(['peminjaman.mahasiswa', 'peminjaman.buku'])->find($id);

        if (!$fine) {
            return response()->json([
                'success' => false,
                'message' => 'Denda tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'fine' => $this->formatFine($fine),
        ]);
    }

    /**
     * Calculate fine for a loan (if not already calculated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateFine(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:pinjamans,id',
        ]);

        try {
            $peminjaman = Peminjaman::with(['mahasiswa', 'buku', 'denda'])->find($request->peminjaman_id);

            if (!$peminjaman) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman tidak ditemukan',
                ], 404);
            }

            // Check if loan is already returned
            if ($peminjaman->status !== 'dikembalikan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pinjaman masih dalam status dipinjam. Proses pengembalian terlebih dahulu.',
                ], 422);
            }

            // If fine already exists, return it
            if ($peminjaman->denda) {
                return response()->json([
                    'success' => true,
                    'message' => 'Denda sudah dihitung sebelumnya',
                    'fine' => $this->formatFine($peminjaman->denda),
                ]);
            }

            // Calculate late days
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali_rencana);
            $returnDate = Carbon::parse($peminjaman->tanggal_kembali_aktual);

            $lateDays = 0;
            if ($returnDate->isAfter($dueDate)) {
                $lateDays = $returnDate->diffInDays($dueDate);
            }

            $fineAmount = $lateDays * self::DAILY_FINE;

            // Create denda record
            $denda = Denda::create([
                'pinjaman_id' => $peminjaman->id,
                'hari_terlambat' => $lateDays,
                'total_denda' => $fineAmount,
                'status_bayar' => $fineAmount > 0 ? 'belum' : 'terbayar',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Denda berhasil dihitung',
                'fine' => $this->formatFine($denda),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung denda: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Record fine payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pay(Request $request)
    {
        $request->validate([
            'denda_id' => 'required|exists:dendas,id',
            'payment_method' => 'nullable|string|in:cash,transfer,card',
            'payment_notes' => 'nullable|string',
        ]);

        try {
            $denda = Denda::find($request->denda_id);

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

            // Update payment status
            $denda->update([
                'status_bayar' => 'terbayar',
                'dibayar_at' => Carbon::now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran denda berhasil dicatat',
                'fine' => $this->formatFine($denda),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get unpaid fines
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnpaid(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $limit = $request->input('limit', 50);

        $unpaidFines = Denda::where('status_bayar', 'belum')
            ->with(['peminjaman.mahasiswa', 'peminjaman.buku'])
            ->limit($limit)
            ->get();

        return response()->json([
            'count' => $unpaidFines->count(),
            'total_unpaid' => $unpaidFines->sum('total_denda'),
            'total_unpaid_formatted' => 'Rp ' . number_format($unpaidFines->sum('total_denda'), 0, ',', '.'),
            'fines' => $unpaidFines->map(fn($fine) => $this->formatFine($fine))->values(),
        ]);
    }

    /**
     * Get fine statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $totalFines = Denda::sum('total_denda');
        $paidFines = Denda::where('status_bayar', 'terbayar')->sum('total_denda');
        $unpaidFines = Denda::where('status_bayar', 'belum')->sum('total_denda');
        $totalRecords = Denda::count();
        $paidRecords = Denda::where('status_bayar', 'terbayar')->count();
        $unpaidRecords = Denda::where('status_bayar', 'belum')->count();

        return response()->json([
            'total_fines' => $totalFines,
            'total_fines_formatted' => 'Rp ' . number_format($totalFines, 0, ',', '.'),
            'paid_fines' => $paidFines,
            'paid_fines_formatted' => 'Rp ' . number_format($paidFines, 0, ',', '.'),
            'unpaid_fines' => $unpaidFines,
            'unpaid_fines_formatted' => 'Rp ' . number_format($unpaidFines, 0, ',', '.'),
            'total_records' => $totalRecords,
            'paid_records' => $paidRecords,
            'unpaid_records' => $unpaidRecords,
            'payment_rate' => $totalRecords > 0 ? round(($paidRecords / $totalRecords) * 100, 2) : 0,
        ]);
    }

    /**
     * Format fine response
     *
     * @param mixed $fine
     * @return array
     */
    private function formatFine($fine)
    {
        return [
            'id' => $fine->id,
            'peminjaman_id' => $fine->pinjaman_id,
            'mahasiswa' => [
                'id' => $fine->peminjaman->mahasiswa->id,
                'nim' => $fine->peminjaman->mahasiswa->nim,
                'nama' => $fine->peminjaman->mahasiswa->nama,
            ],
            'buku' => [
                'id' => $fine->peminjaman->buku->id,
                'nama_buku' => $fine->peminjaman->buku->nama_buku,
            ],
            'hari_terlambat' => $fine->hari_terlambat,
            'total_denda' => $fine->total_denda,
            'total_denda_formatted' => 'Rp ' . number_format($fine->total_denda, 0, ',', '.'),
            'status_bayar' => $fine->status_bayar,
            'dibayar_at' => $fine->dibayar_at,
            'created_at' => $fine->created_at,
            'updated_at' => $fine->updated_at,
        ];
    }
}
