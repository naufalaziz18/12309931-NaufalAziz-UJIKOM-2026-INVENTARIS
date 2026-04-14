<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Borrow;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

// --- IMPORT EXCEL CORE ---
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

// --- IMPORT UNTUK STYLE (BIAR GAK JELEK) ---
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductController extends Controller
{
    // --- AUTH LOGIC ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // CEK ROLE USER SETELAH LOGIN
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.items.index')); // Ke Dashboard Admin
            } elseif ($user->role === 'operator') {
                return redirect()->intended(route('operator.borrow.index')); // Ke Dashboard Operator
            }

            // Default jika role tidak jelas
            return redirect()->intended(route('products.index'));
        }

        return back()->withErrors(['email' => 'Email atau password salah.']);
    }

    // --- VIEW LOGIC ---
    public function index()
    {
        // Pake paginate biar enteng dan support links() di view
        $products = Product::with(['category', 'borrows'])->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function adminIndex()
    {
        // Samakan dengan index biasa, pakai paginate(10)
        $products = Product::with(['category', 'borrows'])->latest()->paginate(10);

        // Sesuaikan variabelnya agar terbaca di view admin.items.index
        return view('admin.items.index', compact('products'));
    }

    /**
     * Form Tambah Barang (Wajib ada karena dipanggil admin.items.create)
     */
    public function create()
    {
        $categories = \App\Models\Category::all();

        // Kalau lu buka halaman create dan muncul layar item, berarti BERHASIL.
        // Setelah itu baru hapus dd-nya.
        // dd($categories); 

        return view('admin.items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'total_stock' => 'required|numeric|min:0', // Samain sama nama di form lu
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'total_stock' => $request->total_stock, // Pakai input total_stock
            'image' => $imagePath, // Tadi lu tulis $path, harusnya $imagePath
            'description' => $request->description, // Jangan lupa dimasukin juga
            'user_id' => auth()->id(),
        ]);

        // Hapus titik di depan 'admin' biar routenya bener
        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.items.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $selisih = $request->stock - $product->total_stock;
        $filename = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && File::exists(public_path('storage/products/' . $product->image))) {
                File::delete(public_path('storage/products/' . $product->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/products'), $filename);
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $filename,
            'total_stock' => $request->stock,
            'stock' => $product->stock + $selisih,
        ]);

        return redirect()->route('admin.items.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && File::exists(public_path('storage/products/' . $product->image))) {
            File::delete(public_path('storage/products/' . $product->image));
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }

    // --- BORROW SYSTEM ---

    public function borrowIndex()
    {
        $borrows = Borrow::with('product')->latest()->get();
        return view('operator.peminjaman', compact('borrows'));
    }

    public function borrowForm()
    {
        $products = Product::where('total_stock', '>', 0)->get();
        return view('operator.create', compact('products'));
    }

    public function processBorrow(Request $request)
    {
        $request->validate([
            'borrower_name' => 'required|string|max:255',
            'return_date' => 'required|date|after:today',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi!");
                    }

                    Borrow::create([
                        'borrower_name' => $request->borrower_name,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'return_date' => $request->return_date,
                        'description' => $item['note'] ?? null,
                        'status' => 'dipinjam',
                        'user_id' => auth()->id(),
                    ]);

                    $product->decrement('total_stock', $item['quantity']);
                }
            });

            return redirect()->route('operator.borrow.index')->with('success', 'Peminjaman berhasil!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function processReturn($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $borrow = Borrow::findOrFail($id);

                if ($borrow->status == 'dikembalikan') {
                    throw new \Exception("Barang ini sudah dikembalikan.");
                }

                $borrow->update([
                    'status' => 'dikembalikan',
                    'actual_return_date' => now()
                ]);

                $product = Product::findOrFail($borrow->product_id);
                $product->increment('stock', $borrow->quantity);
            });

            return back()->with('success', 'Barang berhasil dikembalikan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function resetStock()
    {
        Product::query()->update([
            'stock' => DB::raw('total_stock')
        ]);

        return redirect()->back()->with('success', 'Semua stok telah di-reset.');
    }

    // --- EXPORT EXCEL ---
    public function exportExcelPeminjaman($id)
    {
        $product = Product::with('borrows')->findOrFail($id);

        return Excel::download(
            new class ($product) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithCustomStartCell {

            protected $product;
            private $rowNumber = 0;

            public function __construct($product)
            {
                $this->product = $product;
            }

            public function startCell(): string
            {
                return 'A4'; // Tabel mulai dari baris 4
            }

            public function collection()
            {
                return $this->product->borrows;
            }

            public function headings(): array
            {
                // Selaraskan kolom: Tambah JAM KEMBALI
                return [
                "NO",
                "NAMA PEMINJAM",
                "NAMA BARANG",
                "JUMLAH",
                "TANGGAL PINJAM",
                "BATAS KEMBALI",
                "STATUS",
                "JAM KEMBALI"
                ];
            }

            public function map($borrow): array
            {
                $this->rowNumber++;

                // Logika Jam Kembali (Sinkron dengan PDF & View)
                $jamKembali = '-';
                if ($borrow->status == 'dikembalikan') {
                    $jamKembali = $borrow->actual_return_date
                    ? \Carbon\Carbon::parse($borrow->actual_return_date)->format('d/m/Y H:i') . " WIB"
                    : $borrow->updated_at->format('d/m/Y H:i') . " WIB";
                }

                return [
                    $this->rowNumber,
                    strtoupper($borrow->borrower_name),
                    strtoupper($this->product->name),
                    $borrow->quantity . " Unit",
                    $borrow->created_at->format('d/m/Y H:i') . " WIB",
                    $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : '-',
                    $borrow->status == 'dikembalikan' ? 'SUDAH KEMBALI' : 'MASIH DIPINJAM',
                $jamKembali
                ];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                // 1. Judul Laporan
                $sheet->setCellValue('A1', 'LAPORAN DETAIL PEMINJAMAN BARANG');
                $sheet->mergeCells('A1:H1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // 2. Info Detail Produk
                $sheet->setCellValue('A2', 'ITEM: ' . strtoupper($this->product->name) . ' | TANGGAL CETAK: ' . date('d/m/Y H:i') . ' WIB');
                $sheet->mergeCells('A2:H2');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $highestRow = $sheet->getHighestRow();

                // 3. Styling Header Tabel (Baris 4)
                $sheet->getStyle('A4:H4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'] // Indigo
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center'
                    ]
                ]);

                // 4. Border dan Alignment Data
                $sheet->getStyle('A4:H' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // 5. Tengahin Kolom Tertentu
                $sheet->getStyle('A4:A' . $highestRow)->getAlignment()->setHorizontal('center'); // No
                $sheet->getStyle('D4:H' . $highestRow)->getAlignment()->setHorizontal('center'); // Qty sampai Jam Kembali

                return [];
            }
            },
            'Laporan_Peminjaman_' . str_replace(' ', '_', $product->name) . '_' . date('dmY') . '.xlsx'
        );
    }
    public function exportAllExcel()
    {
        return Excel::download(new class implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithCustomStartCell {

            public function startCell(): string
            {
                return 'A4'; // Data tabel mulai dari sel A4
            }

            public function collection()
            {
                // PERBAIKAN: Pakai withSum untuk menjumlahkan isi kolom 'quantity'
                return \App\Models\Product::with('category')
                    ->withSum([
                        'borrows as total_unit_pinjam' => function ($query) {
                            $query->where('status', 'dipinjam');
                        }
                    ], 'quantity')
                    ->get()
                    ->map(function ($p, $index) {
                        return [
                            $index + 1,
                            strtoupper($p->category->name ?? 'GENERAL'),
                            strtoupper($p->name),
                            $p->total_stock . ' Unit',
                            ($p->total_unit_pinjam ?? 0) . ' Unit', // Realtime & Akurat
                        ];
                    });
            }

            public function headings(): array
            {
                return ["NO", "KATEGORI", "NAMA BARANG", "STOK TERSEDIA", "TOTAL DIPINJAM"];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                // 1. Tambah Judul Utama (Merge sampai kolom E)
                $sheet->setCellValue('A1', 'LAPORAN REKAPITULASI INVENTARIS BARANG');
                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // 2. Info Tanggal Cetak (Merge sampai kolom E)
                $sheet->setCellValue('A2', 'Status Data Per: ' . date('d/m/Y H:i') . ' WIB');
                $sheet->mergeCells('A2:E2');
                $sheet->getStyle('A2')->getFont()->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $highestRow = $sheet->getHighestRow();

                // 3. Styling Header Tabel (Baris 4, Kolom A sampai E)
                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5'] // Biru Indigo
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center'
                    ]
                ]);

                // 4. Border dan Alignment Data (Kolom A sampai E)
                $sheet->getStyle('A4:E' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => 'center'
                    ]
                ]);

                // 5. Tengahin kolom NO, STOK, dan TOTAL DIPINJAM
                $sheet->getStyle('A4:A' . $highestRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D4:E' . $highestRow)->getAlignment()->setHorizontal('center');

                return [];
            }
        }, 'Total_Inventory_' . date('d-m-Y') . '.xlsx');
    }

    // Tambahkan ini di dalam class ProductController
    public function adminExport($id)
    {
        $product = Product::with('borrows')->findOrFail($id);

        return Excel::download(
            new class ($product) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithCustomStartCell {

            protected $product;
            private $rowNumber = 0;

            public function __construct($product)
            {
                $this->product = $product;
            }

            public function startCell(): string
            {
                return 'A4'; // Data mulai di baris 4
            }

            public function collection()
            {
                return $this->product->borrows;
            }

            public function headings(): array
            {
                return ["NO", "NAMA PEMINJAM", "JUMLAH", "TANGGAL PINJAM", "STATUS", "TANGGAL KEMBALI"];
            }

            public function map($borrow): array
            {
                $this->rowNumber++;

                // Logika Jam Kembali yang selaras
                $jamKembali = '-';
                if ($borrow->status == 'dikembalikan') {
                    $jamKembali = $borrow->actual_return_date
                    ? \Carbon\Carbon::parse($borrow->actual_return_date)->format('d/m/Y H:i') . " WIB"
                    : $borrow->updated_at->format('d/m/Y H:i') . " WIB";
                }

                return [
                    $this->rowNumber,
                    strtoupper($borrow->borrower_name),
                    $borrow->quantity . " Unit",
                    $borrow->created_at->format('d/m/Y H:i') . " WIB",
                    strtoupper($borrow->status),
                $jamKembali
                ];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                // 1. Judul Utama
                $sheet->setCellValue('A1', 'LAPORAN DETAIL PEMINJAMAN PER BARANG');
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // 2. Info Barang (Nama Produk)
                $sheet->setCellValue('A2', 'ITEM: ' . strtoupper($this->product->name) . ' | TANGGAL CETAK: ' . date('d/m/Y H:i') . ' WIB');
                $sheet->mergeCells('A2:F2');
                $sheet->getStyle('A2')->getFont()->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $highestRow = $sheet->getHighestRow();

                // 3. Styling Header Tabel (Baris 4)
                $sheet->getStyle('A4:F4')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4338CA'] // Warna Indigo sesuai style PDF lu
                    ],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // 4. Border Seluruh Tabel
                $sheet->getStyle('A4:F' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // 5. Alignment Kolom
                $sheet->getStyle('A4:A' . $highestRow)->getAlignment()->setHorizontal('center'); // No
                $sheet->getStyle('C4:F' . $highestRow)->getAlignment()->setHorizontal('center'); // Qty, Tanggal, Status, Kembali

                return [];
            }
            },
            'Detail_Pinjam_' . str_replace(' ', '_', $product->name) . '_' . date('dmY') . '.xlsx'
        );
    }

    public function exportPdf()
    {
        date_default_timezone_set('Asia/Jakarta');

        $products = Product::with(['category'])
            ->withSum([
                'borrows as total_pinjam' => function ($query) {
                    $query->where('status', 'dipinjam');
                }
            ], 'quantity') // Pakai withSum untuk total unit
            ->get();

        $pdf = Pdf::loadView('admin.items.pdf', compact('products'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Inventory_Items_' . date('d-m-Y') . '.pdf');
    }

    public function exportProductLendingPdf($id)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Tarik produk beserta riwayat peminjamannya
        $product = Product::with(['borrows.user'])->findOrFail($id);

        $pdf = Pdf::loadView('products.lending_pdf', compact('product'))
            ->setPaper('a4', 'landscape'); // Landscape biar kolomnya lega

        return $pdf->download('Laporan_Lending_' . $product->name . '.pdf');
    }

    public function exportBorrowPdf()
    {
        date_default_timezone_set('Asia/Jakarta');

        // Ambil semua data peminjaman
        // Sesuaikan nama Model lu, kalau di route lu 'borrow' biasanya nama modelnya 'Borrow' atau 'Peminjaman'
        $borrows = \App\Models\Borrow::with(['user', 'product'])->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('operator.pdf', compact('borrows'))
            ->setPaper('a4', 'landscape'); // Landscape biar kolomnya lega

        return $pdf->download('Laporan_Peminjaman_Operator_' . date('d-m-Y') . '.pdf');
    }

    public function exportBorrowExcel()
    {
        return Excel::download(new class implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithMapping,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithCustomStartCell {
            // Tentukan data mulai dari sel A4 karena A1-A2 buat Judul
            public function startCell(): string
            {
                return 'A4';
            }

            public function collection()
            {
                return \App\Models\Borrow::with('product')->latest()->get();
            }

            public function headings(): array
            {
                return [
                    "NO",
                    "NAMA PEMINJAM",
                    "BARANG",
                    "JUMLAH",
                    "TANGGAL PINJAM",
                    "BATAS KEMBALI",
                    "STATUS",
                    "JAM KEMBALI"
                ];
            }

            public function map($borrow): array
            {
                static $no = 0;
                $jamKembali = '-';
                if ($borrow->status == 'dikembalikan') {
                    $jamKembali = $borrow->actual_return_date
                        ? \Carbon\Carbon::parse($borrow->actual_return_date)->format('d/m/Y H:i') . " WIB"
                        : $borrow->updated_at->format('d/m/Y H:i') . " WIB";
                }

                return [
                    ++$no,
                    strtoupper($borrow->borrower_name),
                    strtoupper($borrow->product->name),
                    $borrow->quantity . " Unit",
                    $borrow->created_at->format('d/m/Y H:i') . " WIB",
                    $borrow->return_date ? \Carbon\Carbon::parse($borrow->return_date)->format('d/m/Y') : '-',
                    strtoupper($borrow->status),
                    $jamKembali
                ];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                // 1. Tambahin Judul di Baris 1
                $sheet->setCellValue('A1', 'LAPORAN DETAIL PEMINJAMAN BARANG');
                $sheet->mergeCells('A1:H1'); // Gabungin sel A sampe H

                // 2. Tambahin Info Tanggal Cetak di Baris 2
                $sheet->setCellValue('A2', 'Tanggal Cetak: ' . date('d/m/Y H:i') . ' WIB');
                $sheet->mergeCells('A2:H2');

                // 3. Styling Judul (Baris 1)
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // 4. Styling Info Cetak (Baris 2)
                $sheet->getStyle('A2')->getFont()->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                // 5. Styling Header Tabel (Baris 4 - Karena StartCell di A4)
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                // 6. Kasih Border ke seluruh tabel yang ada datanya
                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A4:H' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // 7. Alignment Tengah untuk kolom tertentu (No, Jumlah, Status, Jam)
                $sheet->getStyle('A4:A' . $highestRow)->getAlignment()->setHorizontal('center'); // No
                $sheet->getStyle('D4:D' . $highestRow)->getAlignment()->setHorizontal('center'); // Jumlah
                $sheet->getStyle('G4:H' . $highestRow)->getAlignment()->setHorizontal('center'); // Status & Jam

                return [
                    4 => $headerStyle,
                ];
            }
        }, 'Laporan_Peminjaman_' . date('d-m-Y') . '.xlsx');
    }
    public function show($id)
    {
        // Jika $id bukan angka (misal isinya "export-all" karena salah rute)
        if (!is_numeric($id)) {
            return redirect()->route('products.index');
        }

        return $this->lendingDetails($id);
    }
    public function lendingDetails($id)
    {
        $product = Product::with(['borrows.user', 'category'])->findOrFail($id);
        return view('products.lending_details', compact('product'));
    }
}