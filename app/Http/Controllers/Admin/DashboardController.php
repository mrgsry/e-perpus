<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Mahasiswa;
use App\Models\Peminjaman;
use App\Models\Denda;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats untuk card dashboard
        $totalBuku        = Buku::count();
        $totalMahasiswa   = Mahasiswa::count();
        $peminjamanAktif  = Peminjaman::where('status', 'dipinjam')->count();
        $pendingRequest   = Peminjaman::where('status', 'pending')->count();
        $dendaBelumBayar  = Denda::where('status_bayar', 'belum_bayar')->sum('total_denda');

        // Data tabel terbaru
        $peminjamanTerbaru = Peminjaman::with(['mahasiswa', 'buku'])
                                        ->latest()->take(5)->get();

            // Mahasiswa dengan peminjaman terlambat (status 'terlambat')
            // Filter: Belum dikembalikan (terlambat) ATAU (Sudah dikembalikan terlambat DAN denda belum lunas)
            $stokKritis = Peminjaman::where(function($q) {
                    $q->where('status', 'dipinjam')
                      ->whereDate('tanggal_kembali_rencana', '<', now());
                })
                ->orWhere(function($q) {
                    $q->where('status', 'dikembalikan')
                      ->whereColumn('tanggal_kembali_aktual', '>', 'tanggal_kembali_rencana')
                      ->whereHas('denda', function($d) {
                          $d->where('status_bayar', '!=', 'lunas');
                      });
                })
                ->with(['mahasiswa', 'buku', 'denda'])
                ->get();

        // Data peminjaman per jurusan untuk donut chart
        $peminjamanPerJurusan = Peminjaman::join('mahasiswas', 'pinjamans.mahasiswa_id', '=', 'mahasiswas.id')
            ->select('mahasiswas.jurusan', DB::raw('count(*) as total'))
            ->groupBy('mahasiswas.jurusan')
            ->orderBy('total', 'desc')
            ->get();

        // Data pendaftaran mahasiswa per hari (30 hari terakhir)
        $pendaftaranPerHariRaw = Mahasiswa::select(
                DB::raw("DATE(created_at) as tanggal"),
                DB::raw("count(*) as total")
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Generate 30 hari terakhir untuk label chart pendaftaran
        $pendaftaranLabels = [];
        $pendaftaranData = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $pendaftaranLabels[] = $date->format('d M');
            
            $found = $pendaftaranPerHariRaw->firstWhere('tanggal', $dateStr);
            $pendaftaranData[] = $found ? $found->total : 0;
        }

        // Data buku dipinjam per hari (14 hari terakhir)
        $bukuDipinjamPerHari = Peminjaman::select(
                DB::raw("DATE(tanggal_pinjam) as tanggal"),
                DB::raw("count(*) as total")
            )
            ->whereNotNull('tanggal_pinjam')
            ->where('tanggal_pinjam', '>=', now()->subDays(14))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Data buku dikembalikan per hari (14 hari terakhir)
        $bukuDikembalikanPerHari = Peminjaman::select(
                DB::raw("DATE(tanggal_kembali_aktual) as tanggal"),
                DB::raw("count(*) as total")
            )
            ->whereNotNull('tanggal_kembali_aktual')
            ->where('tanggal_kembali_aktual', '>=', now()->subDays(14))
            ->where('status', 'dikembalikan')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Generate 14 hari terakhir untuk label chart
        $chartLabels = [];
        $chartDataPinjam = [];
        $chartDataKembali = [];
        
        for ($i = 13; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            
            // Cari data pinjam untuk tanggal ini
            $pinjam = $bukuDipinjamPerHari->firstWhere('tanggal', $dateStr);
            $chartDataPinjam[] = $pinjam ? $pinjam->total : 0;
            
            // Cari data kembali untuk tanggal ini
            $kembali = $bukuDikembalikanPerHari->firstWhere('tanggal', $dateStr);
            $chartDataKembali[] = $kembali ? $kembali->total : 0;
        }

        return view('admin.dashboard.index', compact(
            'totalBuku',
            'totalMahasiswa',
            'peminjamanAktif',
            'pendingRequest',
            'dendaBelumBayar',
            'peminjamanTerbaru',
            'stokKritis',
            'peminjamanPerJurusan',
            'pendaftaranLabels',
            'pendaftaranData',
            'chartLabels',
            'chartDataPinjam',
            'chartDataKembali'
        ));
    }
}
