<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->call([
            UserSeeder::class,
            ProfilPmiSeeder::class,
            CoaSeeder::class,
            ProgramKerjaSeeder::class,
            LaporanKeuanganSeeder::class,
            PenerimaanKasSeeder::class,
            PengeluaranKasSeeder::class,
            PenyesuaianSeeder::class,
        ]);

        // 🔥 HIDUPKAN KEMBALI FK CHECK
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
