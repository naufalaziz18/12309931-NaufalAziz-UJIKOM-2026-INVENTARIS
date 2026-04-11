<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrower_name',
        'product_id',
        'user_id',
        'quantity',
        'return_date',
        'description',
        'status',
    ];

    /**
     * Relasi ke Barang (Product)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke Operator (User)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}