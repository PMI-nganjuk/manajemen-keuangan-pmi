<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LaporanKeuangan;

class LaporanKeuanganSeeder extends Seeder
{
    public function run(): void
    {
        // disable foreign key checks to allow truncate when FK constraints exist
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        LaporanKeuangan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        LaporanKeuangan::create([
            'periode' => 'Desember 2025',
            'tahun' => 2025,
            'status' => 'draft',
            'kas_tahun1' => 0,
            'kas_tahun2' => 0,
            'saldo_akhir' => 0,
        ]);
    }
}
