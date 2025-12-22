<?php

namespace Database\Seeders;

use App\Models\PenerimaanKas;
use Illuminate\Database\Seeder;
use App\Models\PengeluaranKas;

class PengeluaranKasSeeder extends Seeder
{
    public function run(): void
    {
        PengeluaranKas::query()->delete();

        PengeluaranKas::create([
            'tanggal' => '2025-01-10',
            'no_dokumen' => 'PPJ-001',
            'referensi' => 'PO-2025-01',
            'rupiah' => 5000000,
            'keterangan' => 'Pembelian alat kesehatan',

            // Foreign keys
            'id_user' => 1,
            'id_coa' => '4-100',
            'id_program_kerja' => 1,
            'id_laporan' => 1,
        ]);
    }
}
