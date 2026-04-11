<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Tambahkan proteksi internal agar hanya role admin yang bisa akses
     * meskipun user mencoba ngetik manual URL-nya.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                return redirect('/dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Mengambil kategori + hitung jumlah produk (products_count)
        $categories = Category::withCount('products')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_pj' => 'required|string|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'division_pj' => $request->division_pj,
        ]);

        // Pindah ke index biar langsung liat hasilnya
        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan, Bray!');
    }

    // Menggunakan Route Model Binding ($category langsung jadi object)
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'division_pj' => 'required|string|max:255'
        ]);

        $category->update($request->all());

        return redirect()->route('products.admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('products.admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize {
            public function collection()
            {
                // Mengambil kategori beserta jumlah produk (menggunakan withCount)
                return Category::withCount('products')->get()->map(function ($c, $index) {
                    return [
                        'No' => $index + 1,
                        'Nama Kategori' => $c->name,
                        'PJ Divisi' => $c->division_pj,
                        'Total Produk' => $c->products_count . ' Items',
                    ];
                });
            }
            public function headings(): array
            {
                return ["NO", "NAMA KATEGORI", "PJ DIVISI", "TOTAL PRODUK"];
            }
        }, 'Kategori_Inventory_' . date('d-m-Y') . '.xlsx');
    }
}