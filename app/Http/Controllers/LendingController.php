<?php
namespace App\Http\Controllers;

use App\Models\Lending;
use Illuminate\Http\Request;

class LendingController extends Controller
{
    public function index()
    {
        $lendings = Lending::all();
        return view('lendings.index', compact('lendings'));
    }

    public function show($id)
    {
        // 1. Panggil Model PRODUCT, bukan Lending!
        // Karena kita mau liat detail barang "Macbook" atau "HT"
        $product = \App\Models\Product::findOrFail($id);

        // 2. Ambil semua transaksi peminjaman yang punya product_id tersebut
        $lendings = Lending::where('product_id', $id)
            ->where('status', 'not returned')
            ->with('user')
            ->get();

        return view('lendings.show', compact('product', 'lendings'));
    }

    // Fungsi lainnya (create, store, edit, dsb) sesuaikan nanti
}