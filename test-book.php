<?php

require __DIR__ . '/vendor/autoload.php';

use App\Models\Buku;

// Fetch first 5 books
$books = Buku::select('id', 'nama_buku', 'penerbit', 'jenis_buku', 'stok_total', 'stok_tersedia')
    ->limit(5)
    ->get();

foreach ($books as $book) {
    echo "ID: {$book->id}\n";
    echo "Nama: {$book->nama_buku}\n";
    echo "Penerbit: {$book->penerbit}\n";
    echo "Jenis: {$book->jenis_buku}\n";
    echo "Stok Total: {$book->stok_total}\n";
    echo "Stok Tersedia: {$book->stok_tersedia}\n";
    echo "--------------\n";
}
