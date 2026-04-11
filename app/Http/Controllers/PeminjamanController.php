<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'borrower_name' => 'required',
            'return_date' => 'required|date',
            'items' => 'required|array'
        ]);

        // 2. Simpan (Pakai DB Transaction biar aman)
        \DB::transaction(function () use ($request) {
            $peminjaman = \App\Models\Peminjaman::create([
                'borrower_name' => $request->borrower_name,
                'return_date' => $request->return_date,
                'status' => 'pending'
            ]);

            foreach ($request->items as $item) {
                // Simpan detail (Sesuaikan nama table/model detail lo)
                \DB::table('peminjaman_details')->insert([
                    'peminjaman_id' => $peminjaman->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'],
                    'created_at' => now(),
                ]);

                // Kurangi stok (Pakai kolom total_stock sesuai hasil tinker lo)
                \App\Models\Product::where('id', $item['product_id'])
                    ->decrement('total_stock', $item['quantity']);
            }
        });

        // 3. REDIRECT BALIK KE HALAMAN UTAMA
        return redirect()->route('operator.borrow.index')->with('success', 'Berhasil Add Data!');
    }
}