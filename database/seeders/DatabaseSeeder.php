<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
   // data dummy tabel users
    public function run(): void
    {
        // Data default untuk Admin
        User::create([
            'nama' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // pastikan nanti diganti di production
            'role' => User::ROLE_ADMIN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081234567890',
            'alamat' => 'Kantor Pusat',
        ]);

        // Data default untuk Manager Keuangan
        User::create([
            'nama' => 'Manager Keuangan',
            'email' => 'manager.keuangan@example.com',
            'password' => Hash::make('manager123'),
            'role' => User::ROLE_MANAGER_KEUANGAN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081298765432',
            'alamat' => 'Kantor Pusat',
        ]);

        // Data default untuk Staf Keuangan
        User::create([
            'nama' => 'Staf Keuangan',
            'email' => 'staf.keuangan@example.com',
            'password' => Hash::make('staf123'),
            'role' => User::ROLE_STAF_KEUANGAN,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081277889900',
            'alamat' => 'Kantor Cabang',
        ]);

        // Data default untuk Pegawai biasa
        User::create([
            'nama' => 'Pegawai Biasa',
            'email' => 'pegawai@example.com',
            'password' => Hash::make('pegawai123'),
            'role' => User::ROLE_PEGAWAI,
            'kategori' => User::KATEGORI_KARYAWAN,
            'nomer_wa' => '081200011122',
            'alamat' => 'Kantor Cabang',
        ]);
    }
}
