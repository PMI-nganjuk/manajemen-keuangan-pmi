<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PengeluaranKas;

class PengeluaranKasSeeder extends Seeder
{
    public function run(): void
    {
        PengeluaranKas::truncate();

        PengeluaranKas::create([
            'tanggal' => '2025-01-10',
            'no_dokumen' => 'PPJ-001',
            'referensi' => 'PO-2025-01',
            'rupiah' => 5000000,
            'keterangan' => 'Pembelian alat kesehatan',
            // foreign keys (ensure these referenced records exist in the related tables)
            'id_user' => 1,
            'id_coa' => '4-100',
            'id_program_kerja' => 1,
            'id_laporan' => 1,
        ]);
    }
}
