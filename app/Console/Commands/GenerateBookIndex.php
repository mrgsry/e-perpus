<?php

namespace App\Console\Commands;

use App\Models\Buku;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateBookIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:generate-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate JSON index of all books for AI indexing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating book index...');

        $books = Buku::all();
        $index = [
            'generated_at' => now()->toIso8601String(),
            'total_books' => $books->count(),
            'books' => [],
        ];

        foreach ($books as $book) {
            $stokTersedia = $book->stok_tersedia ?? 0;
            $stokTotal = $book->stok_total ?? 0;

            $index['books'][] = [
                'id' => $book->id,
                'nama_buku' => $book->nama_buku,
                'jenis_buku' => $book->jenis_buku ?? 'Tidak Diketahui',
                'genre_buku' => $book->genre_buku ?? 'Tidak Diketahui',
                'penerbit' => $book->penerbit ?? 'Tidak Diketahui',
                'stok_total' => $stokTotal,
                'stok_tersedia' => $stokTersedia,
                'kategori' => $book->jenis_buku ?? 'Tidak Diketahui',
                'genre' => $book->genre_buku ?? 'Tidak Diketahui',
                'status_ketersediaan' => $stokTersedia > 0 ? 'Tersedia' : 'Habis',
                'view_count' => $book->view_count ?? 0,
                'borrow_count' => $book->borrow_count ?? 0,
                'popularitas' => $book->borrow_count > 10 ? 'Sangat Populer' : ($book->borrow_count > 5 ? 'Populer' : 'Biasa'),
                'deskripsi_lengkap' => "Buku berjudul '{$book->nama_buku}' termasuk dalam kategori {$book->jenis_buku} dengan genre {$book->genre_buku}. Diterbitkan oleh {$book->penerbit}. Stok tersedia: {$stokTersedia} dari {$stokTotal} total. Buku ini telah dipinjam {$book->borrow_count} kali dan dilihat {$book->view_count} kali.",
                'keywords' => $this->generateKeywords($book),
            ];
        }

        // Save to storage
        Storage::put('private/book-index.json', json_encode($index, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info('Book index generated successfully!');
        $this->info('Total books indexed: ' . $books->count());
        $this->info('File saved to: storage/app/book-index.json');

        return Command::SUCCESS;
    }

    /**
     * Generate searchable keywords for a book
     *
     * @param Buku $book
     * @return array
     */
    private function generateKeywords(Buku $book): array
    {
        $keywords = [];

        // Add book name words
        $keywords = array_merge($keywords, explode(' ', strtolower($book->nama_buku)));

        // Add genre
        if ($book->genre_buku) {
            $keywords[] = strtolower($book->genre_buku);
        }

        // Add category
        if ($book->jenis_buku) {
            $keywords[] = strtolower($book->jenis_buku);
        }

        // Add publisher
        if ($book->penerbit) {
            $keywords = array_merge($keywords, explode(' ', strtolower($book->penerbit)));
        }

        // Remove duplicates and empty values
        $keywords = array_unique(array_filter($keywords, function ($keyword) {
            return strlen($keyword) > 2; // Only keep keywords longer than 2 characters
        }));

        return array_values($keywords);
    }
}
