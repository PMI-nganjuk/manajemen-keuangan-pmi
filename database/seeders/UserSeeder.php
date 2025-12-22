<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081234567890',
            'alamat' => 'Kantor PMI',
        ]);

        User::create([
            'nama' => 'Manager Keuangan',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
            'role' => User::ROLE_MANAGER_KEUANGAN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081298765432',
            'alamat' => 'PMI Cabang',
        ]);

        User::create([
            'nama' => 'Staf Keuangan',
            'email' => 'staf@example.com',
            'password' => Hash::make('staf123'),
            'role' => User::ROLE_STAF_KEUANGAN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081277889900',
            'alamat' => 'PMI UDD',
        ]);
    }
}
