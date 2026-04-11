<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $role;

    // 1. Terima parameter role dari Controller
    public function __construct($role)
    {
        $this->role = $role;
    }

    /**
     * 2. Filter data berdasarkan role yang dikirim
     */
    public function collection()
    {
        return User::where('role', $this->role)->get();
    }

    /**
     * 3. Header kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Lengkap',
            'Email',
            'Role',
            'Tanggal Dibuat',
        ];
    }

    /**
     * 4. Mapping data agar rapi
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            strtoupper($user->role),
            $user->created_at->format('d-m-Y H:i'),
        ];
    }
}