<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Penyesuaian;

class PenyesuaianSeeder extends Seeder
{
    public function run(): void
    {
        // disable foreign key checks to allow truncate when FK constraints exist
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Penyesuaian::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Penyesuaian::create([
            'tanggal' => '2025-12-31',
            'no_dokumen' => 'PNY-2025-001',
            'referensi' => 'ADJ-2025',
            'debit' => 1500000,
            'kredit' => 0,
            'keterangan' => 'Penyesuaian akhir tahun',
            'saldo_awal' => 0,

            // Foreign keys
            'id_coa' => '4-100',
            'id_program_kerja' => 1,
            'id_laporan' => 1,
        ]);
    }
}
