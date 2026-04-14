<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    /**
     * Menampilkan daftar Admin
     */
    public function index()
    {
        $users = User::where('role', 'admin')->latest()->paginate(10);
        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Data Admin'
        ]);
    }

    public function indexAdmin()
    {
        return view('admin.users.index', [
            'users' => User::where('role', 'admin')->paginate(10),
            'title' => 'Data Admin' // <-- Ini wajib ada
        ]);
    }

    public function indexOperator()
    {
        $users = User::where('role', 'operator')->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Data Operator'
        ]);
    }

    /**
     * Export Excel berdasarkan role
     */
    public function exportExcel($role)
    {
        // Pastikan role huruf kapital untuk judul & nama file
        $displayRole = ucfirst($role);
        $fileName = 'Data_User_' . $displayRole . '_' . date('d-m-Y') . '.xlsx';

        return Excel::download(
            new class ($role, $displayRole) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\ShouldAutoSize,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithCustomStartCell {

            protected $role;
            protected $displayRole;

            public function __construct($role, $displayRole)
            {
                $this->role = $role;
                $this->displayRole = $displayRole;
            }

            public function startCell(): string
            {
                return 'A4'; // Selaras: tabel mulai di baris 4
            }

            public function collection()
            {
                // Ambil user berdasarkan role
                return \App\Models\User::where('role', $this->role)->get()->map(function ($u, $index) {
                    return [
                        $index + 1,
                        strtoupper($u->name),
                        $u->email,
                        strtoupper($u->role),
                        $u->created_at->format('d/m/Y'),
                    ];
                });
            }

            public function headings(): array
            {
                return ["NO", "NAMA LENGKAP", "EMAIL", "ROLE", "TANGGAL JOIN"];
            }

            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
            {
                // 1. Judul Utama (Baris 1)
                $sheet->setCellValue('A1', 'LAPORAN DATA USER - ' . strtoupper($this->displayRole));
                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

                // 2. Info Tanggal Cetak (Baris 2)
                $sheet->setCellValue('A2', 'Dicetak pada: ' . date('d/m/Y H:i') . ' WIB');
                $sheet->mergeCells('A2:E2');
                $sheet->getStyle('A2')->getFont()->setItalic(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

                $highestRow = $sheet->getHighestRow();

                // 3. Styling Header Tabel (Baris 4) - Biru Indigo selaras dengan Produk
                $sheet->getStyle('A4:E4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F46E5']
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center'
                    ]
                ]);

                // 4. Border Hitam Pekat & Alignment
                $sheet->getStyle('A4:E' . $highestRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // 5. Tengahin kolom NO, ROLE, dan TANGGAL
                $sheet->getStyle('A5:A' . $highestRow)->getAlignment()->setHorizontal('center');
                $sheet->getStyle('D5:E' . $highestRow)->getAlignment()->setHorizontal('center');

                return [];
            }
            },
            $fileName
        );
    }

    /**
     * Form Tambah User
     */
    public function create(Request $request)
    {
        // Ambil role dari URL (admin atau operator)
        $role = $request->query('role', 'admin');
        return view('admin.users.create', [
            'title' => 'Tambah ' . ucfirst($role),
            'role' => $role
        ]);
    }

    /**
     * Simpan User Baru (Oleh Admin)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,operator',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // LOGIKA REDIRECT NYAMBUNG:
        // Jika yang dibuat admin, balik ke index admin. Jika operator, balik ke index operator.
        if ($user->role == 'admin') {
            return redirect()->route('admin.users.index')->with('success', 'Admin berhasil ditambahkan!');
        } else {
            return redirect()->route('admin.users.operator')->with('success', 'Operator berhasil ditambahkan!');
        }
    }

    /**
     * Menampilkan Form Edit Profil (Diri Sendiri)
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', [
            'user' => $user,
            'title' => 'Edit Profil' // Tambahkan ini
        ]);
    }

    /**
     * Update Profil & Password (Diri Sendiri)
     * Digunakan oleh Admin & Operator
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ];

        // 2. Kalau password diisi, validasi password lama & baru
        if ($request->filled('password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['password'] = 'required|min:8|confirmed';
        }

        $request->validate($rules);

        // 3. Update Name & Email
        $user->name = $request->name;
        $user->email = $request->email;

        // 4. Update Password kalau ada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil dan password berhasil diperbarui!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', [
            'user' => $user,
            'title' => 'Edit Data User' // Tambahkan ini
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,operator',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // LOGIKA REDIRECT BIAR GAK NYASAR:
        if ($user->role == 'admin') {
            return redirect()->route('admin.users.index')->with('success', 'Data Admin berhasil diupdate');
        } else {
            return redirect()->route('admin.users.operator')->with('success', 'Data Operator berhasil diupdate');
        }
    }

    public function exportPdf($role)
    {
        date_default_timezone_set('Asia/Jakarta');

        // Ambil user sesuai role yang diklik (admin atau operator)
        $users = User::where('role', $role)->get();
        $title = "Data " . ucfirst($role);

        $pdf = Pdf::loadView('admin.users.pdf', compact('users', 'title'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_User_' . $role . '_' . date('d-m-Y') . '.pdf');
    }

    /**
     * Hapus User
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}