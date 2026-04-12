<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function collection()
    {
        return User::where('role', $this->role)
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->get()
            ->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    strtoupper($user->role),
                    $user->created_at->format('d M Y'),
                ];
            });
    }

    // Header tabel
    public function headings(): array
    {
        return [
            'ID',
            'NAMA LENGKAP',
            'ALAMAT EMAIL',
            'ROLE ACCESS',
            'TANGGAL DAFTAR',
        ];
    }

    // Styling biar cantik
    public function styles(Worksheet $sheet)
    {
        return [
            // Style Header (Baris 1)
            1    => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1A202C'] // Warna Navy Sidebar lo
                ],
                'alignment' => ['horizontal' => 'center']
            ],

            // Kasih border ke semua data yang ada
            'A1:E' . ($sheet->getHighestRow()) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'E2E8F0'],
                    ],
                ],
            ],
        ];
    }
}