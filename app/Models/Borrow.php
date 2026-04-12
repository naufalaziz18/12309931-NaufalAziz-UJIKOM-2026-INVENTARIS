<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    // Tambahin ini kalau kolom di DB lo beda sama standar Laravel
    protected $fillable = [
        'borrower_name',
        'product_id',
        'user_id',
        'quantity',
        'return_date',
        'description',
        'status'
    ];

    // 1. Relasi ke Product (Ini yang dipake buat update stok)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 2. Relasi ke User (Ini yang dipake buat "Edited By")
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}