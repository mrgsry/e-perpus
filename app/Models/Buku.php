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

    /**
     * The "booted" method of the model.
     * Auto-regenerate JSON index on book modifications.
     */
    protected static function booted()
    {
        static::saved(function ($book) {
            try {
                \Illuminate\Support\Facades\Artisan::queue('books:generate-index');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to regenerate book index on saved', [
                    'message' => $e->getMessage()
                ]);
            }
        });

        static::deleted(function ($book) {
            try {
                \Illuminate\Support\Facades\Artisan::queue('books:generate-index');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to regenerate book index on deleted', [
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    public function pinjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function reviews()
    {
        return $this->hasMany(BookReview::class);
    }
}
