<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Get all loans (paginated)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'status' => 'nullable|string|in:dipinjam,dikembalikan,pending',
        ]);

        $perPage = $request->input('per_page', 15);
        $status = $request->input('status');

        $query = Peminjaman::with(['mahasiswa', 'buku']);

        if ($status) {
            $query->where('status', $status);
        }

        $loans = $query->paginate($perPage);

        return response()->json([
            'count' => $loans->count(),
            'total' => $loans->total(),
            'current_page' => $loans->current_page(),
            'last_page' => $loans->last_page(),
            'loans' => $loans->map(fn($loan) => $this->formatLoan($loan))->values(),
        ]);
    }

    /**
     * Create a new loan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'buku_id' => 'required|exists:bukus,id',
            'tanggal_kembali_rencana' => 'required|date|after:today',
        ]);

        try {
            // Check book stock
            $buku = Buku::find($request->buku_id);
            if ($buku->stok_tersedia <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok buku tidak tersedia',
                ], 422);
            }

            // Check if student has active loan for same book
            $existingLoan = Peminjaman::where('mahasiswa_id', $request->mahasiswa_id)
                ->where('buku_id', $request->buku_id)
                ->where('status', 'dipinjam')
                ->first();

            if ($existingLoan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mahasiswa sudah meminjam buku ini',
                ], 422);
            }

            $loan = Peminjaman::create([
                'mahasiswa_id' => $request->mahasiswa_id,
                'buku_id' => $request->buku_id,
                'tanggal_pinjam' => Carbon::now(),
                'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
                'status' => 'dipinjam',
            ]);

            // Update book stock
            $buku->decrement('stok_tersedia');

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dibuat',
                'loan' => $this->formatLoan($loan->load(['mahasiswa', 'buku'])),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pinjaman: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get loan by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $loan = Peminjaman::with(['mahasiswa', 'buku', 'denda'])->find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'loan' => [
                'id' => $loan->id,
                'mahasiswa' => [
                    'id' => $loan->mahasiswa->id,
                    'nim' => $loan->mahasiswa->nim,
                    'nama' => $loan->mahasiswa->nama,
                ],
                'buku' => [
                    'id' => $loan->buku->id,
                    'nama_buku' => $loan->buku->nama_buku,
                    'penerbit' => $loan->buku->penerbit,
                ],
                'tanggal_pinjam' => $loan->tanggal_pinjam,
                'tanggal_kembali_rencana' => $loan->tanggal_kembali_rencana,
                'tanggal_kembali_aktual' => $loan->tanggal_kembali_aktual,
                'status' => $loan->status,
                'denda' => $loan->denda ? [
                    'id' => $loan->denda->id,
                    'hari_terlambat' => $loan->denda->hari_terlambat,
                    'total_denda' => $loan->denda->total_denda,
                    'status_bayar' => $loan->denda->status_bayar,
                ] : null,
                'created_at' => $loan->created_at,
                'updated_at' => $loan->updated_at,
            ],
        ]);
    }

    /**
     * Update loan
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $loan = Peminjaman::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan',
            ], 404);
        }

        $request->validate([
            'tanggal_kembali_rencana' => 'nullable|date|after:today',
            'status' => 'nullable|string|in:dipinjam,dikembalikan,pending',
        ]);

        try {
            $loan->update($request->only(['tanggal_kembali_rencana', 'status']));

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil diupdate',
                'loan' => $this->formatLoan($loan->load(['mahasiswa', 'buku'])),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update pinjaman: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete loan
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $loan = Peminjaman::find($id);

        if (!$loan) {
            return response()->json([
                'success' => false,
                'message' => 'Pinjaman tidak ditemukan',
            ], 404);
        }

        try {
            // Return book stock if loan is still active
            if ($loan->status === 'dipinjam') {
                $loan->buku->increment('stok_tersedia');
            }

            $loan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pinjaman berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pinjaman: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Search loans by student name or NIM
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $request->input('query');
        $limit = $request->input('limit', 10);

        $loans = Peminjaman::whereHas('mahasiswa', function ($q) use ($query) {
            $q->where('nama', 'LIKE', '%' . $query . '%')
              ->orWhere('nim', 'LIKE', '%' . $query . '%');
        })
            ->orWhereHas('buku', function ($q) use ($query) {
                $q->where('nama_buku', 'LIKE', '%' . $query . '%');
            })
            ->with(['mahasiswa', 'buku'])
            ->limit($limit)
            ->get();

        return response()->json([
            'found' => $loans->isNotEmpty(),
            'count' => $loans->count(),
            'loans' => $loans->map(fn($loan) => $this->formatLoan($loan))->values(),
        ]);
    }

    /**
     * Get active loans (not yet returned)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActive(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $limit = $request->input('limit', 50);

        $loans = Peminjaman::where('status', 'dipinjam')
            ->with(['mahasiswa', 'buku'])
            ->limit($limit)
            ->get();

        return response()->json([
            'count' => $loans->count(),
            'loans' => $loans->map(fn($loan) => $this->formatLoan($loan))->values(),
        ]);
    }

    /**
     * Format loan response
     *
     * @param mixed $loan
     * @return array
     */
    private function formatLoan($loan)
    {
        return [
            'id' => $loan->id,
            'mahasiswa' => [
                'id' => $loan->mahasiswa->id,
                'nim' => $loan->mahasiswa->nim,
                'nama' => $loan->mahasiswa->nama,
            ],
            'buku' => [
                'id' => $loan->buku->id,
                'nama_buku' => $loan->buku->nama_buku,
                'penerbit' => $loan->buku->penerbit,
            ],
            'tanggal_pinjam' => $loan->tanggal_pinjam,
            'tanggal_kembali_rencana' => $loan->tanggal_kembali_rencana,
            'tanggal_kembali_aktual' => $loan->tanggal_kembali_aktual,
            'status' => $loan->status,
        ];
    }
}
