<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProgramKerja;

class ProgramKerjaSeeder extends Seeder
{
    public function run(): void
    {
        ProgramKerja::truncate();

        ProgramKerja::create([
            'nama_program' => 'Pelayanan Kesehatan',
            'keterangan' => 'Program pelayanan kesehatan masyarakat',
            'id_pegawai' => 1, // Admin
        ]);

        ProgramKerja::create([
            'nama_program' => 'Pengembangan Organisasi',
            'keterangan' => 'Peningkatan kapasitas internal PMI',
            'id_pegawai' => 2, // Manager
        ]);
    }
}
