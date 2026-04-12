<?php

namespace App\Http\Controllers;

use App\Models\Borrow;  // Pakai model ini
use App\Models\Product; // Pastikan ini juga diimport
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'borrower_name' => 'required|string',
            'return_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // 2. Gunakan DB Transaction biar kalau satu gagal, semua batal
            \DB::transaction(function () use ($request) {

                foreach ($request->items as $item) {
                    // Simpan ke model Borrow sesuai kolom hasil Tinker
                    \App\Models\Borrow::create([
                        'borrower_name' => $request->borrower_name,
                        'product_id' => $item['product_id'],
                        'user_id' => auth()->id(), // Pastikan lo sudah login!
                        'quantity' => $item['quantity'],
                        'return_date' => $request->return_date,
                        'description' => $item['note'] ?? '-', // note dari form ke description di DB
                        'status' => 'dipinjam',
                    ]);

                    // 3. Potong stok produk otomatis
                    \App\Models\Product::where('id', $item['product_id'])
                        ->decrement('total_stock', $item['quantity']);
                }
            });

            // 4. Redirect ke index dengan pesan sukses
            return redirect()->route('operator.borrow.index')
                ->with('success', 'Data peminjaman berhasil disimpan!');

        } catch (\Exception $e) {
            // Jika ada error (misal: user_id null karena belum login), dia bakal nampilin pesan ini
            return back()->withErrors(['error' => 'Gagal simpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function return($id)
    {
        // 1. Cari data peminjaman
        $borrow = \App\Models\Borrow::findOrFail($id);

        // 2. Proteksi: Kalau sudah dikembalikan, jangan proses lagi
        if ($borrow->status === 'dikembalikan') {
            return redirect()->back()->with('error', 'Barang ini sudah berstatus dikembalikan.');
        }

        try {
            DB::transaction(function () use ($borrow) {
                // 3. UPDATE STATUS ke dikembalikan
                $borrow->update([
                    'status' => 'dikembalikan'
                ]);

                // 4. BALIKIN STOK ke tabel products
                // Kita ambil produk terkait lewat relasi, lalu tambah stoknya
                $borrow->product->increment('total_stock', $borrow->quantity);
            });

            return redirect()->route('operator.borrow.index')
                ->with('success', 'Status Berhasil Diperbarui: Barang Telah Kembali!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }
}