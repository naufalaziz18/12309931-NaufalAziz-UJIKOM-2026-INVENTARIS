<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Nama tabel di Postgres lo
    protected $table = 'peminjamans'; 

    // Field yang boleh diisi (sesuaiin sama kolom di DB lo)
    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status'
    ];

    // Opsional: Kalau lo mau datanya otomatis bawa info User & Buku
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}