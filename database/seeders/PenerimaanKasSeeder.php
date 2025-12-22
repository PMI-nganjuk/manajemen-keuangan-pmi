<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PenerimaanKas;

class PenerimaanKasSeeder extends Seeder
{
    public function run(): void
    {
        PenerimaanKas::truncate();

        PenerimaanKas::create([
            'tanggal' => '2025-01-05',
            'no_dokumen' => 'PMJ-001',
            'referensi' => 'DON-2025-01',
            'rupiah' => 25000000,
            'keterangan' => 'Donasi awal tahun',
            // foreign keys (ensure these referenced records exist in the related tables)
            'id_user' => 1,
            'id_coa' => '4-100',
            'id_program_kerja' => 1,
            'id_laporan' => 1,
        ]);
    }
}
