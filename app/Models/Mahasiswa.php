<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Model
{
    use Notifiable;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'jurusan',
        'no_telepon',
        'angkatan',
        'email',
        'status',
        'referral_token',
    ];

    public function pinjamans() {
        return $this->hasMany(Peminjaman::class);
    }
}