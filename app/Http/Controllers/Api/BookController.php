<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Get book stock by name
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStock(Request $request)
    {
        $request->validate([
            'book_name' => 'required|string',
        ]);

        $bookName = $request->input('book_name');
        
        $books = Buku::where('nama_buku', 'LIKE', '%' . $bookName . '%')
            ->select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')
            ->get();

        if ($books->isEmpty()) {
            return response()->json([
                'found' => false,
                'message' => 'Buku tidak ditemukan',
            ]);
        }

        return response()->json([
            'found' => true,
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'nama_buku' => $book->nama_buku,
                    'penerbit' => $book->penerbit,
                    'jenis_buku' => $book->jenis_buku,
                    'stok_total' => $book->stok_total,
                    'stok_tersedia' => $book->stok_tersedia,
                    'status' => $book->stok_tersedia > 0 ? 'tersedia' : 'habis',
                ];
            })->toArray(),
        ]);
    }

    /**
     * Search books by keyword
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $keyword = $request->input('keyword');
        $limit = $request->input('limit', 5);

        $books = Buku::where('nama_buku', 'LIKE', '%' . $keyword . '%')
            ->orWhere('penerbit', 'LIKE', '%' . $keyword . '%')
            ->orWhere('jenis_buku', 'LIKE', '%' . $keyword . '%')
            ->select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')
            ->limit($limit)
            ->get();

        return response()->json([
            'found' => $books->isNotEmpty(),
            'count' => $books->count(),
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'nama_buku' => $book->nama_buku,
                    'penerbit' => $book->penerbit,
                    'jenis_buku' => $book->jenis_buku,
                    'stok_total' => $book->stok_total,
                    'stok_tersedia' => $book->stok_tersedia,
                    'status' => $book->stok_tersedia > 0 ? 'tersedia' : 'habis',
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get available books
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailable(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $limit = $request->input('limit', 10);

        $books = Buku::where('stok_tersedia', '>', 0)
            ->select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')
            ->limit($limit)
            ->get();

        return response()->json([
            'count' => $books->count(),
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'nama_buku' => $book->nama_buku,
                    'penerbit' => $book->penerbit,
                    'jenis_buku' => $book->jenis_buku,
                    'stok_tersedia' => $book->stok_tersedia,
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get books by category
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByCategory(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $category = $request->input('category');
        $limit = $request->input('limit', 10);

        $books = Buku::where('jenis_buku', 'LIKE', '%' . $category . '%')
            ->select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')
            ->limit($limit)
            ->get();

        return response()->json([
            'found' => $books->isNotEmpty(),
            'category' => $category,
            'count' => $books->count(),
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'nama_buku' => $book->nama_buku,
                    'penerbit' => $book->penerbit,
                    'jenis_buku' => $book->jenis_buku,
                    'stok_tersedia' => $book->stok_tersedia,
                    'status' => $book->stok_tersedia > 0 ? 'tersedia' : 'habis',
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get popular books
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPopular(Request $request)
    {
        $request->validate([
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $limit = $request->input('limit', 5);

        $books = Buku::orderBy('borrow_count', 'desc')
            ->select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia', 'borrow_count')
            ->limit($limit)
            ->get();

        return response()->json([
            'count' => $books->count(),
            'books' => $books->map(function ($book) {
                return [
                    'id' => $book->id,
                    'nama_buku' => $book->nama_buku,
                    'penerbit' => $book->penerbit,
                    'jenis_buku' => $book->jenis_buku,
                    'stok_tersedia' => $book->stok_tersedia,
                    'borrow_count' => $book->borrow_count ?? 0,
                    'status' => $book->stok_tersedia > 0 ? 'tersedia' : 'habis',
                ];
            })->toArray(),
        ]);
    }

    /**
     * Get total books statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStats()
    {
        $totalBooks = Buku::count();
        $totalStokTersedia = Buku::sum('stok_tersedia');
        $totalStokTotal = Buku::sum('stok_total');
        $totalDipinjam = $totalStokTotal - $totalStokTersedia;
        $bukuTersedia = Buku::where('stok_tersedia', '>', 0)->count();

        return response()->json([
            'total_buku' => $totalBooks,
            'total_stok' => $totalStokTotal,
            'stok_tersedia' => $totalStokTersedia,
            'stok_dipinjam' => $totalDipinjam,
            'buku_tersedia' => $bukuTersedia,
            'buku_habis' => $totalBooks - $bukuTersedia,
        ]);
    }
}