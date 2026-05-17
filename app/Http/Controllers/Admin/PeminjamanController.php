<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QrCodeService;
use App\Models\Peminjaman;
use App\Models\History;
use App\Exports\PeminjamanExport;
use App\Notifications\PeminjamanApproved;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamanController extends Controller
{
    public function index()
    {
        $pending = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $aktif = Peminjaman::with(['mahasiswa', 'buku'])
            ->where('status', 'dipinjam')
            ->latest()
            ->get();

        return view('admin.peminjaman.index', compact('pending', 'aktif'));
    }

    public function approve(Request $request, $id)
    {
        $pinjaman = Peminjaman::with(['mahasiswa', 'buku'])->findOrFail($id);

        // Generate QR Code
        $qrService = new QrCodeService();
        $qrPath    = $qrService->generate($pinjaman->booking_id);

        $pinjaman->update([
            'status'                  => 'dipinjam',
            'tanggal_pinjam'          => now(),
            'tanggal_kembali_rencana' => now()->addDays(7),
            'qr_code_path'            => $qrPath,
            'approved_at'             => now(),
            'approved_by'             => session('admin_id'),
        ]);

        // Kurangi stok buku
        $pinjaman->buku->decrement('stok_tersedia');
        
        // Increment borrow count
        $pinjaman->buku->increment('borrow_count');

        // Catat history
        History::create([
            'pinjaman_id'    => $pinjaman->id,
            'aksi'           => 'Dipinjam',
            'keterangan'     => 'Diapprove oleh admin',
            'dilakukan_oleh' => session('admin_name'),
        ]);

        // Kirim email notifikasi ke mahasiswa
        $pinjaman->mahasiswa->notify(new PeminjamanApproved($pinjaman));

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil di-approve! QR Code telah dibuat dan email notifikasi telah dikirim.'
        ]);
    }

    public function tolak($id)
    {
        $pinjaman = Peminjaman::findOrFail($id);
        $pinjaman->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil ditolak.'
        ]);
    }

    public function scanQr()
    {
        return view('admin.peminjaman.scan-qr');
    }

    public function exportPdf()
    {
        $pinjamans = Peminjaman::with(['mahasiswa', 'buku'])->latest()->get();
        $pdf = Pdf::loadView('admin.peminjaman.pdf', compact('pinjamans'));
        return $pdf->download('laporan-peminjaman-' . date('Ymd') . '.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new PeminjamanExport(), 'peminjaman-' . date('Ymd') . '.xlsx');
    }
    
}