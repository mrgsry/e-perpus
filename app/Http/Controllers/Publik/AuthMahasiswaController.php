<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Services\DendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthMahasiswaController extends Controller
{
    public function showLoginForm()
    {
        if (Session::has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.dashboard');
        }

        return view('publik.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nim'   => 'required|string',
            'token' => 'required|string|size:6',
        ]);

        $mahasiswa = Mahasiswa::where('nim', $request->nim)
            ->where('referral_token', strtoupper($request->token))
            ->where('status', 'approved')
            ->first();

        if (!$mahasiswa) {
            return back()
                ->withErrors(['msg' => 'NIM atau token tidak valid, atau akun mahasiswa belum disetujui.'])
                ->withInput($request->only('nim'));
        }

        Session::put('mahasiswa_id', $mahasiswa->id);

        return redirect()
            ->route('mahasiswa.dashboard')
            ->with('login_success', 'Login berhasil. Selamat datang, ' . $mahasiswa->nama . '!');
    }

    public function dashboard()
    {
        if (!Session::has('mahasiswa_id')) {
            return redirect()->route('mahasiswa.login')
                ->withErrors(['msg' => 'Silakan login terlebih dahulu.']);
        }

        $mahasiswa = Mahasiswa::findOrFail(Session::get('mahasiswa_id'));
        $peminjaman = Peminjaman::with(['buku', 'denda'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->get();

        $dendaService = new DendaService();
        $pinjamanTerlambatAktif = $peminjaman->filter(function ($item) use ($dendaService) {
            if ($item->status === 'terlambat') {
                return true;
            }

            if ($item->status === 'dipinjam' && $item->tanggal_kembali_rencana) {
                return $dendaService->hitungDenda($item)['terlambat'];
            }

            return false;
        });

        $dendaBelumBayar = Denda::whereHas('peminjaman', function ($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->where('status_bayar', 'belum_bayar')
            ->get();

        $totalDendaBelumBayar = $dendaBelumBayar->sum('total_denda');
        $hariTerlambatAktif = $pinjamanTerlambatAktif->sum(function ($item) use ($dendaService) {
            if ($item->denda) {
                return (int) $item->denda->hari_terlambat;
            }

            if ($item->tanggal_kembali_rencana) {
                return (int) $dendaService->hitungDenda($item)['hari_terlambat'];
            }

            return 0;
        });
        $punyaBlokirPeminjaman = $pinjamanTerlambatAktif->isNotEmpty() || $dendaBelumBayar->isNotEmpty();

        $totalPeminjaman = $peminjaman->count();
        $sedangDipinjam = $peminjaman->where('status', 'dipinjam')->count();
        $sudahDikembalikan = $peminjaman->where('status', 'dikembalikan')->count();
        $terlambatCount = $pinjamanTerlambatAktif->count();
        $kreditSkor = max(0, 100 - ($terlambatCount * 10) - ($dendaBelumBayar->count() * 5));

        return view('publik.mahasiswa.index', compact(
            'mahasiswa',
            'peminjaman',
            'totalPeminjaman',
            'sedangDipinjam',
            'sudahDikembalikan',
            'terlambatCount',
            'kreditSkor',
            'pinjamanTerlambatAktif',
            'dendaBelumBayar',
            'totalDendaBelumBayar',
            'hariTerlambatAktif',
            'punyaBlokirPeminjaman'
        ));
    }

    public function logout()
    {
        Session::forget('mahasiswa_id');
        return redirect()->route('mahasiswa.login');
    }
}
