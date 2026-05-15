<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BukuExport;

class BukuController extends Controller
{
    public function index() {
        $bukus = Buku::latest()->get();
        return view('admin.buku.index', compact('bukus'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_buku'   => 'required|string|max:200',
            'penerbit'    => 'required|string',
            'jenis_buku'  => 'required|string',
            'genre_buku'  => 'required|string',
            'stok_total'  => 'required|integer|min:0',
            'sampul_buku' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_ebook'  => 'nullable|mimes:pdf|max:10240',
        ]);

        $sampulPath = null;
        if ($request->hasFile('sampul_buku')) {
            $sampulPath = $request->file('sampul_buku')->store('sampul', 'public');
        }

        $ebookPath = null;
        if ($request->hasFile('file_ebook')) {
            $ebookPath = $request->file('file_ebook')->store('ebooks', 'public');
        }

        Buku::create([
            'nama_buku'     => $request->nama_buku,
            'penerbit'      => $request->penerbit,
            'jenis_buku'    => $request->jenis_buku,
            'genre_buku'    => $request->genre_buku,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => $request->stok_total,
            'sampul_buku'   => $sampulPath,
            'file_ebook'    => $ebookPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Buku berhasil ditambahkan!']);
    }

    public function update(Request $request, $id) {
        $buku = Buku::findOrFail($id);
        $request->validate([
            'nama_buku'  => 'required|string|max:200',
            'penerbit'   => 'required|string',
            'jenis_buku' => 'required|string',
            'genre_buku' => 'required|string',
            'stok_total' => 'required|integer|min:0',
            'file_ebook' => 'nullable|mimes:pdf|max:10240',
        ]);

        $sampulPath = $buku->sampul_buku;
        if ($request->hasFile('sampul_buku')) {
            if ($sampulPath) Storage::disk('public')->delete($sampulPath);
            $sampulPath = $request->file('sampul_buku')->store('sampul', 'public');
        }

        $ebookPath = $buku->file_ebook;
        if ($request->hasFile('file_ebook')) {
            if ($ebookPath) Storage::disk('public')->delete($ebookPath);
            $ebookPath = $request->file('file_ebook')->store('ebooks', 'public');
        }

        $selisih = $request->stok_total - $buku->stok_total;
        $buku->update([
            'nama_buku'     => $request->nama_buku,
            'penerbit'      => $request->penerbit,
            'jenis_buku'    => $request->jenis_buku,
            'genre_buku'    => $request->genre_buku,
            'stok_total'    => $request->stok_total,
            'stok_tersedia' => max(0, $buku->stok_tersedia + $selisih),
            'sampul_buku'   => $sampulPath,
            'file_ebook'    => $ebookPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Buku berhasil diupdate!']);
    }

    public function destroy($id) {
        $buku = Buku::findOrFail($id);
        
        // Cek apakah buku pernah dipinjam
        if ($buku->pinjamans()->exists()) {
            return response()->json([
                'success' => false, 
                'message' => 'Buku tidak dapat dihapus karena memiliki riwayat peminjaman. Untuk keamanan data, buku yang pernah dipinjam tidak boleh dihapus.'
            ], 400);
        }
        
        // Hapus file sampul dan ebook jika ada
        if ($buku->sampul_buku) {
            Storage::disk('public')->delete($buku->sampul_buku);
        }
        if ($buku->file_ebook) {
            Storage::disk('public')->delete($buku->file_ebook);
        }
        
        $buku->delete();
        return response()->json(['success' => true, 'message' => 'Buku berhasil dihapus!']);
    }

    public function exportPdf() {
        $bukus = Buku::all();
        $pdf = Pdf::loadView('admin.buku.pdf', compact('bukus'));
        return $pdf->download('laporan-buku-'.date('Ymd').'.pdf');
    }

    public function exportExcel() {
        return Excel::download(new BukuExport(), 'buku-'.date('Ymd').'.xlsx');
    }
}