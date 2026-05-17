<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Services\DendaService;
use Illuminate\Http\Request;

class PinjamController extends Controller
{
    public function index()
    {
        return view('publik.form-pinjam');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'nim'            => 'required|string',
            'jurusan'        => 'required|string',
            'no_telepon'     => 'required|string',
            'referral_token' => 'required|string',
            'buku_ids'       => 'required|array|min:1|max:3',
            'buku_ids.*'     => 'exists:bukus,id',
        ]);

        // Cek NIM terdaftar
        $mahasiswa = Mahasiswa::where('nim', $request->nim)->first();
        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak terdaftar. Silakan daftar terlebih dahulu.'
            ], 422);
        }

         // Cek status mahasiswa
         if ($mahasiswa->status !== 'approved') {
             $statusMessage = $mahasiswa->status === 'pending' 
                 ? 'Pendaftaran Anda masih menunggu persetujuan admin.' 
                 : 'Pendaftaran Anda ditolak. Hubungi admin untuk informasi lebih lanjut.';
             
             return response()->json([
                 'success' => false,
                 'message' => $statusMessage
             ], 422);
         }

         // Cek token referral
         if (empty($mahasiswa->referral_token) || $request->referral_token !== $mahasiswa->referral_token) {
             return response()->json([
                 'success' => false,
                 'message' => 'Kode referral tidak valid atau belum terdaftar. Silakan periksa token Anda.'
             ], 422);
         }

        // Cek apakah masih ada pinjaman aktif
        $aktivPinjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->count();

        if ($aktivPinjaman >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki 3 peminjaman aktif. Kembalikan buku terlebih dahulu.'
            ], 422);
        }

        $dendaService = new DendaService();
        $bookingIds   = [];

        foreach ($request->buku_ids as $bukuId) {
            $buku = Buku::findOrFail($bukuId);

            if ($buku->stok_tersedia < 1) {
                return response()->json([
                    'success' => false,
                    'message' => "Buku '{$buku->nama_buku}' stoknya habis."
                ], 422);
            }

            $bookingId = $dendaService->generateBookingId();

            Peminjaman::create([
                'booking_id'   => $bookingId,
                'mahasiswa_id' => $mahasiswa->id,
                'buku_id'      => $bukuId,
                'status'       => 'pending',
            ]);

            $bookingIds[] = $bookingId;
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Peminjaman berhasil diajukan! Tunggu persetujuan admin.',
            'booking_ids' => $bookingIds,
            'nama'        => $mahasiswa->nama,
        ]);
    }

    public function konfirmasi($booking_id)
    {
        $peminjaman = Peminjaman::with(['mahasiswa', 'buku'])
                              ->where('booking_id', $booking_id)
                              ->firstOrFail();

        return view('publik.pinjam.konfirmasi', compact('peminjaman'));
    }

    public function cekStatus()
    {
        return view('publik.pinjam.cek-status');
    }
    
  public function cekStatusPost(Request $request)
{
    $request->validate([
        'booking_id' => 'required|string',
    ]);

    // Bersihkan prefix URL jika ada (dari scan QR)
    $bookingId = preg_replace('/^.*\//', '', trim($request->booking_id));

    $peminjaman = Peminjaman::where('booking_id', $bookingId)
        ->with(['buku', 'mahasiswa', 'denda'])
        ->first();

    if (!$peminjaman) {
        return response()->json([
            'success' => false,
            'message' => 'Booking ID tidak ditemukan. Periksa kembali ID yang Anda masukkan.',
        ], 404);
    }

    // Hitung denda realtime jika masih dipinjam
    $dendaRealtime   = null;
    $hariTerlambat   = 0;
    if ($peminjaman->status === 'dipinjam') {
        $dendaService  = new DendaService();
        $hasil         = $dendaService->hitungDenda($peminjaman);
        $hariTerlambat = $hasil['hari_terlambat'];
        $dendaRealtime = $hasil['terlambat'] ? $hasil['total_denda'] : null;
    }

    // Tentukan status label & warna untuk frontend
    $statusMap = [
        'pending'    => ['label' => 'Menunggu Persetujuan', 'color' => 'warning'],
        'dipinjam'   => ['label' => 'Sedang Dipinjam',      'color' => 'primary'],
        'dikembalikan' => ['label' => 'Sudah Dikembalikan', 'color' => 'success'],
        'ditolak'    => ['label' => 'Ditolak',              'color' => 'danger'],
    ];

    $statusInfo = $statusMap[$peminjaman->status] ?? ['label' => ucfirst($peminjaman->status), 'color' => 'secondary'];

    return response()->json([
        'success' => true,
        'data' => [
            'booking_id'             => $peminjaman->booking_id,
            'mahasiswa'              => $peminjaman->mahasiswa->nama . ' (' . $peminjaman->mahasiswa->nim . ')',
            'buku'                   => $peminjaman->buku->nama_buku,
            'tanggal_pinjam'         => $peminjaman->tanggal_pinjam
                                            ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y')
                                            : '-',
            'tanggal_kembali_rencana'=> $peminjaman->tanggal_kembali_rencana
                                            ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y')
                                            : '-',
            'tanggal_kembali_aktual' => $peminjaman->tanggal_kembali_aktual
                                            ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_aktual)->format('d/m/Y')
                                            : 'Belum dikembalikan',
            'status'                 => $statusInfo['label'],
            'status_color'           => $statusInfo['color'],
            'denda'                  => $dendaRealtime
                                            ? 'Rp ' . number_format($dendaRealtime, 0, ',', '.') . ' (' . $hariTerlambat . ' hari)'
                                            : null,
            'qr_code_url'            => $peminjaman->qr_code_path
                                            ? asset('storage/' . $peminjaman->qr_code_path)
                                            : null,
        ],
    ]);
}

    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|size:6',
        ]);

        $token = strtoupper($request->token);
        $mahasiswa = Mahasiswa::where('referral_token', $token)
            ->where('status', 'approved')
            ->first();

        if (!$mahasiswa) {
            return response()->json([
                'valid' => false,
                'message' => 'Token referral tidak valid atau tidak ditemukan.',
            ]);
        }

        return response()->json([
            'valid' => true,
            'mahasiswa' => [
                'nama' => $mahasiswa->nama,
                'nim' => $mahasiswa->nim
            ]
        ]);
    }

    public function getMahasiswaByNim($nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(['success' => false, 'message' => 'NIM tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama'       => $mahasiswa->nama,
                'jurusan'    => $mahasiswa->jurusan,
                'no_telepon' => $mahasiswa->no_telepon,
            ]
        ]);
    }

    public function cekNim(Request $request)
    {
        $request->validate([
            'nim' => 'required|string',
            'referral_token' => 'required|string|size:6',
        ]);

        $nim = $request->nim;
        $token = strtoupper($request->referral_token);

        // Cek NIM terdaftar
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIM tidak ditemukan dalam sistem.',
            ], 404);
        }

        // Cek status mahasiswa
        if ($mahasiswa->status !== 'approved') {
            $statusMessage = $mahasiswa->status === 'pending' 
                ? 'Pendaftaran Anda masih menunggu persetujuan admin.' 
                : 'Pendaftaran Anda ditolak. Hubungi admin untuk informasi lebih lanjut.';
            
            return response()->json([
                'success' => false,
                'message' => $statusMessage
            ], 422);
        }

        // Cek token referral
        if (empty($mahasiswa->referral_token) || $token !== $mahasiswa->referral_token) {
            return response()->json([
                'success' => false,
                'message' => 'Token referral tidak valid atau tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'              => $mahasiswa->id,
                'nim'             => $mahasiswa->nim,
                'nama'            => $mahasiswa->nama,
                'jurusan'         => $mahasiswa->jurusan,
                'no_telepon'      => $mahasiswa->no_telepon,
                'referral_token'  => $mahasiswa->referral_token,
            ]
        ]);
    }

    public function getPeminjamanByQr(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
        ]);

        $peminjaman = Peminjaman::where('booking_id', $request->booking_id)
            ->with(['mahasiswa', 'buku'])
            ->first();

        if (!$peminjaman) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id'         => $peminjaman->id,
                'booking_id' => $peminjaman->booking_id,
                'status'     => $peminjaman->status,
                'mahasiswa'  => [
                    'nama'       => $peminjaman->mahasiswa->nama,
                    'nim'        => $peminjaman->mahasiswa->nim,
                    'jurusan'    => $peminjaman->mahasiswa->jurusan,
                    'no_telepon' => $peminjaman->mahasiswa->no_telepon,
                ],
                'buku' => [
                    'nama_buku' => $peminjaman->buku->nama_buku,
                    'pengarang' => $peminjaman->buku->pengarang,
                    'penerbit'  => $peminjaman->buku->penerbit,
                    'kategori'  => $peminjaman->buku->kategori,
                ],
            ]
        ]);
    }
}
