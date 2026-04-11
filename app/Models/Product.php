<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // ... fillable lu yang tadi ...
    protected $fillable = ['name', 'category_id', 'total_stock', 'image', 'description', 'user_id'];

    /**
     * Relasi ke data Peminjaman (Satu barang bisa dipinjam berkali-kali)
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * Jangan lupa relasi ke Category (Kalau belum ada)
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke User yang input barang
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvailableStockAttribute()
    {
        $borrowed = $this->borrows()->where('status', 'borrowed')->count();
        return $this->total_stock - $borrowed;
    }

    public function scopeAvailable($query)
    {
        // Ganti 'stock' jadi 'total_stock' di sini juga
        return $query->where('total_stock', '>', 0);
    }
}