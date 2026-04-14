<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
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

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithStyles {

            public function collection()
            {
                // Mengambil kategori beserta jumlah produk (menggunakan withCount)
                return \App\Models\Category::withCount('products')->get()->map(function ($c, $index) {
                    return [
                        $index + 1,
                        strtoupper($c->name),      // Samakan jadi kapital biar tegas
                        strtoupper($c->division_pj ?? '-'), // Kapital juga buat PJ Divisi
                        $c->products_count . ' Items',
                    ];
                });
            }

            public function headings(): array
            {
                return ["NO", "NAMA KATEGORI", "PJ DIVISI", "TOTAL PRODUK"];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                return [
                    // Style Header - Biru cerah sesuai tema inventory lo
                    1 => [
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FFFFFF'],
                            'size' => 12
                        ],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => '2E2EFE'] // Biru cerah
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                        ]
                    ],

                    // Style Border - Hitam tipis merata di seluruh sel data
                    'A1:' . $highestColumn . $highestRow => [
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                        'alignment' => [
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ],
                    ],
                ];
            }
        }, 'Kategori_Inventory_' . date('d-m-Y') . '.xlsx');
    }

    public function exportPdf()
    {
        // Set timezone ke WIB secara global untuk fungsi ini
        date_default_timezone_set('Asia/Jakarta');

        $categories = Category::withCount('products')->get();

        $pdf = Pdf::loadView('admin.categories.pdf', compact('categories'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Kategori_' . date('d-m-Y') . '.pdf');
    }
}