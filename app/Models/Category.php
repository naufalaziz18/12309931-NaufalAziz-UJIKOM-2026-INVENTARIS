<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Tambahkan ini agar bisa mengisi data lewat Controller
    protected $fillable = ['name', 'division_pj'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}