<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin - Gunakan updateOrCreate juga!
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Cek berdasarkan ini
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Akun Operator - Sudah benar
        \App\Models\User::updateOrCreate(
            ['email' => 'operator@gmail.com'],
            [
                'name' => 'Operator System',
                'password' => bcrypt('password'),
                'role' => 'operator',
            ]
        );
    }
}
