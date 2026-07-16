<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\History;
use App\Models\Denda;
use App\Services\DendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    public function index()
    {
        $pinjamans = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        $totalPengembalian = Peminjaman::where('status', 'dikembalikan')->count();
        $pengembalianTerlambat = Peminjaman::where(function ($query) {
                $query->where('status', 'terlambat')
                    ->orWhere(function ($activeLoanQuery) {
                        $activeLoanQuery->where('status', 'dipinjam')
                            ->whereDate('tanggal_kembali_rencana', '<', today());
                    });
            })
            ->count();
        $pengembalianTepatWaktu = Peminjaman::where('status', 'dikembalikan')
            ->where(function ($query) {
                $query->whereColumn('tanggal_kembali_aktual', '<=', 'tanggal_kembali_rencana')
                    ->orWhereNull('tanggal_kembali_aktual');
            })
            ->count();
        $belumKembali = Peminjaman::where('status', 'dipinjam')->count();

        $terlambatPerJurusan = Peminjaman::query()
            ->join('mahasiswas', 'mahasiswas.id', '=', 'pinjamans.mahasiswa_id')
            ->where(function ($query) {
                $query->where('pinjamans.status', 'terlambat')
                    ->orWhere(function ($activeLoanQuery) {
                        $activeLoanQuery->where('pinjamans.status', 'dipinjam')
                            ->whereDate('pinjamans.tanggal_kembali_rencana', '<', today());
                    });
            })
            ->selectRaw("COALESCE(mahasiswas.jurusan, 'Belum diisi') as jurusan, COUNT(*) as total")
            ->groupBy('mahasiswas.jurusan')
            ->orderByDesc('total')
            ->get();

        $pengembalianChartData = json_encode([$pengembalianTerlambat, $pengembalianTepatWaktu]);
        $jurusanTerlambatLabels = $terlambatPerJurusan->pluck('jurusan')->toJson();
        $jurusanTerlambatData = $terlambatPerJurusan->pluck('total')->toJson();

        return view('admin.pengembalian.index', compact(
            'pinjamans',
            'totalPengembalian',
            'pengembalianTerlambat',
            'pengembalianTepatWaktu',
            'belumKembali',
            'pengembalianChartData',
            'jurusanTerlambatLabels',
            'jurusanTerlambatData'
        ));
    }

    public function scanQr()
    {
        return view('admin.pengembalian.scan-qr');
    }

    public function getByBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
        ]);

        // Bersihkan prefix "Booking ID: " jika ada (dari hasil scan QR)
        $bookingId = preg_replace('/^Booking\s*ID\s*[:\-]\s*/i', '', $request->booking_id);
        $bookingId = trim($bookingId);

        $pinjaman = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('booking_id', $bookingId)
            ->first();

        if (!$pinjaman) {
            return response()->json([
                'success' => false,
                'message' => 'Booking ID tidak ditemukan: ' . $bookingId,
            ], 404);
        }

        $dendaService = new DendaService();
        $denda = null;
        if ($pinjaman->status === 'dipinjam') {
            $denda = $dendaService->hitungDenda($pinjaman);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'         => $pinjaman->id,
                'booking_id' => $pinjaman->booking_id,
                'status'     => $pinjaman->status,
                'tanggal_pinjam' => $pinjaman->tanggal_pinjam
                    ? Carbon::parse($pinjaman->tanggal_pinjam)->format('d/m/Y')
                    : null,
                'tanggal_kembali_rencana' => $pinjaman->tanggal_kembali_rencana
                    ? Carbon::parse($pinjaman->tanggal_kembali_rencana)->format('d/m/Y')
                    : null,
                'mahasiswa' => [
                    'nama'       => $pinjaman->mahasiswa?->nama,
                    'nim'        => $pinjaman->mahasiswa?->nim,
                    'jurusan'    => $pinjaman->mahasiswa?->jurusan,
                    'no_telepon' => $pinjaman->mahasiswa?->no_telepon,
                ],
                'buku' => [
                    'nama_buku' => $pinjaman->buku?->nama_buku,
                    'pengarang' => $pinjaman->buku?->pengarang,
                    'penerbit'  => $pinjaman->buku?->penerbit,
                    'kategori'  => $pinjaman->buku?->kategori,
                ],
                'denda' => $denda,
            ],
        ]);
    }

    public function proses(Request $request, $id)
    {
        $pinjaman = Peminjaman::with(['buku', 'mahasiswa', 'denda'])->findOrFail($id);

        if ($pinjaman->status !== 'dipinjam') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya peminjaman yang sedang dipinjam dapat dikembalikan.',
            ], 400);
        }

        $dendaService = new DendaService();
        $dendaData    = $dendaService->hitungDenda($pinjaman);

        $pinjaman->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => Carbon::now(),
        ]);

        $pinjaman->buku->increment('stok_tersedia');

        if ($dendaData['total_denda'] > 0) {
            Denda::updateOrCreate(
                ['pinjaman_id' => $pinjaman->id],
                [
                    'hari_terlambat' => $dendaData['hari_terlambat'],
                    'total_denda'    => $dendaData['total_denda'],
                    'status_bayar'   => 'belum_bayar',
                ]
            );
        }

        History::create([
            'pinjaman_id'   => $pinjaman->id,
            'aksi'          => 'Dikembalikan',
            'keterangan'    => $dendaData['total_denda'] > 0
                ? 'Dikembalikan dengan denda terlambat.'
                : 'Dikembalikan tepat waktu.',
            'dilakukan_oleh' => session('admin_name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian buku berhasil diproses.',
            'denda'   => $dendaData,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $pinjaman = Peminjaman::findOrFail($id);

        $pinjaman->update([
            'status'                 => 'dikembalikan',
            'tanggal_kembali_aktual' => Carbon::now(),
        ]);

        $pinjaman->buku->increment('stok_tersedia');

        History::create([
            'pinjaman_id'    => $pinjaman->id,
            'aksi'           => 'Dikembalikan',
            'keterangan'     => 'Dikonfirmasi pengembalian oleh admin',
            'dilakukan_oleh' => session('admin_name'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengembalian buku berhasil dikonfirmasi!',
        ]);
    }
}