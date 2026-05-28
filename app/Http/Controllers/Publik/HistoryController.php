<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HistoryController extends Controller
{
    public function showForm()
    {
        if (Session::has('mahasiswa_id')) {
            $mahasiswa = Mahasiswa::where('id', Session::get('mahasiswa_id'))
                ->where('status', 'approved')
                ->first();

            if ($mahasiswa) {
                $peminjaman = Peminjaman::with('buku')
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

                $terlambatCount = $peminjaman->where('status', 'terlambat')->count();
                $kreditSkor = max(0, 100 - ($terlambatCount * 10));

                return view('publik.mahasiswa.history', [
                    'mahasiswa'  => $mahasiswa,
                    'peminjaman' => $peminjaman,
                    'kreditSkor' => $kreditSkor,
                ]);
            }

            Session::forget('mahasiswa_id');
        }

        return view('publik.mahasiswa.history');
    }

    public function show(Request $request)
    {
        $request->validate([
            'nim'   => 'required|string',
            'token' => 'required|string|size:6',
        ]);

        $nim   = $request->nim;
        $token = strtoupper($request->token);

        $mahasiswa = Mahasiswa::where('nim', $nim)
            ->where('referral_token', $token)
            ->where('status', 'approved')
            ->first();

        if (! $mahasiswa) {
            return back()->withErrors(['msg' => 'NIM atau token tidak valid, atau mahasiswa belum disetujui.']);
        }

        $peminjaman = Peminjaman::with('buku')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Hitung kredit skor (contoh: 100 - (jumlah peminjaman terlambat * 10))
        $terlambatCount = $peminjaman->where('status', 'terlambat')->count();
        $kreditSkor = max(0, 100 - ($terlambatCount * 10));

        return view('publik.mahasiswa.history', [
            'mahasiswa'   => $mahasiswa,
            'peminjaman'  => $peminjaman,
            'kreditSkor'  => $kreditSkor,
        ]);
    }
}