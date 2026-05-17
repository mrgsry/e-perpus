<?php

namespace App\Http\Controllers\Publik;

use App\Http\Controllers\Controller;
use App\Models\Buku;

class KatalogController extends Controller
{
    public function index()
    {
        $bukus = Buku::where('stok_tersedia', '>', 0)
                     ->orWhere('stok_tersedia', '=', 0)
                     ->latest()
                     ->get();

        $jenisBuku = Buku::select('jenis_buku')->distinct()->pluck('jenis_buku');

        return view('publik.katalog', compact('bukus', 'jenisBuku'));
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        
        // Increment view count
        $buku->increment('view_count');
        
        return response()->json($buku);
    }

    public function ebookReader($id)
    {
        $buku = Buku::findOrFail($id);
        
        if (!$buku->file_ebook) {
            abort(404, 'E-book tidak tersedia');
        }

        // Increment view count when opening ebook reader
        $buku->increment('view_count');

        return view('publik.ebook-reader', compact('buku'));
    }

    public function streamPdf($id)
    {
        $buku = Buku::findOrFail($id);
        
        if (!$buku->file_ebook) {
            abort(404);
        }

        $path = storage_path('app/public/' . $buku->file_ebook);
        
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="ebook.pdf"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store',
        ]);
    }
}
