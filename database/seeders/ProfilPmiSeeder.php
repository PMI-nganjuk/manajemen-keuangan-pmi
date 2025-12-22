<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfilPmi;

class ProfilPmiSeeder extends Seeder
{
    public function run(): void
    {
        ProfilPmi::truncate();

        ProfilPmi::create([
            'nama_pmi' => 'PALANG MERAH INDONESIA KABUPATEN NGANJUK',
            'alamat' => 'Jl. Mayjend Sungkono No. 10 Nganjuk',
            'ketua' => 'Drs. Lishandoyo, M.Si',
            'kepala_markas' => 'Luhur Budi Wahyono, SE, MM',
            'kepala_uud' => 'Herin Purnawati, S.Pd',
            'periode_buku_awal' => '2025-01-01',
            'periode_buku_akhir' => '2025-12-31',
            'tahun_buku' => 2025,
        ]);
    }
}
