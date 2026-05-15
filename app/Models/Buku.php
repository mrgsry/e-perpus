<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'bukus';

    protected $fillable = [
        'nama_buku',
        'penerbit',
        'jenis_buku',
        'genre_buku',
        'sampul_buku',
        'file_ebook',
        'stok_total',
        'stok_tersedia',
        'view_count',
        'borrow_count',
    ];

    public function pinjamans() {
        return $this->hasMany(Peminjaman::class);
    }
}