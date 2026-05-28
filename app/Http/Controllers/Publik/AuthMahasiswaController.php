<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Models\LoginLog; // Assuming a LoginLog model exists
use App\Services\DendaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // Data for Donut Chart: Peminjaman per Judul
        $peminjamanPerJudul = Peminjaman::select('buku_id', DB::raw('count(*) as total'))
            ->where('mahasiswa_id', $mahasiswa->id)
            ->groupBy('buku_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                // Load buku relation manually
                $buku = \App\Models\Buku::find($item->buku_id);
                return (object)[
                    'buku_judul' => $buku->nama_buku ?? $buku->judul ?? 'Judul Tidak Diketahui',
                    'total' => $item->total
                ];
            });

        // Data for Line Chart: Session Login per hari selama 7 hari terakhir berdasarkan tanggal realtime aplikasi
        $timezone = config('app.timezone', 'Asia/Jakarta');
        $today = Carbon::now($timezone)->startOfDay();

        $login7Hari = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->format('Y-m-d');

            $login7Hari->push((object)[
                'date' => Carbon::parse($date, $timezone)->format('d M'),
                'total' => 0, // Placeholder - will show 0 until login tracking is implemented
            ]);
        }

        // Data for Bar Chart: History peminjaman dan pengembalian mahasiswa selama 7 hari terakhir
        $riwayatPeminjamanMap = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('tanggal_pinjam')
            ->whereDate('tanggal_pinjam', '>=', $today->copy()->subDays(6)->toDateString())
            ->get()
            ->groupBy(function ($item) use ($timezone) {
                return Carbon::parse($item->tanggal_pinjam, $timezone)->format('Y-m-d');
            })
            ->map(fn($items) => $items->count());

        $riwayatPengembalianMap = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->whereNotNull('tanggal_kembali_aktual')
            ->whereDate('tanggal_kembali_aktual', '>=', $today->copy()->subDays(6)->toDateString())
            ->get()
            ->groupBy(function ($item) use ($timezone) {
                return Carbon::parse($item->tanggal_kembali_aktual, $timezone)->format('Y-m-d');
            })
            ->map(fn($items) => $items->count());

        $historyPeminjamanPengembalian = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->format('Y-m-d');

            $historyPeminjamanPengembalian->push((object)[
                'date' => Carbon::parse($date, $timezone)->format('d M'),
                'peminjaman' => $riwayatPeminjamanMap->get($date, 0),
                'pengembalian' => $riwayatPengembalianMap->get($date, 0),
            ]);
        }

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
/*  */            }

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
            'punyaBlokirPeminjaman',
            'peminjamanPerJudul',
            'login7Hari',
            'historyPeminjamanPengembalian'
        ));
    }

    
    public function updateRequest(Request $request)
    {
        if (!Session::has('mahasiswa_id')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $mahasiswa = Mahasiswa::findOrFail(Session::get('mahasiswa_id'));
        
        $data = $request->only(['nama', 'email', 'jurusan', 'no_telepon']);
        
        // Validation (optional but recommended)
        $request->validate([
            'nama' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'jurusan' => 'sometimes|string|max:255',
            'no_telepon' => 'sometimes|string|max:20',
        ]);

        $mahasiswa->update([
            'pending_updates' => $data
        ]);

        return response()->json(['success' => true, 'message' => 'Permintaan update data telah dikirim dan menunggu persetujuan admin.']);
    }

    public function logout()
    {
        Session::forget('mahasiswa_id');
        return redirect()->route('mahasiswa.login');
    }
}