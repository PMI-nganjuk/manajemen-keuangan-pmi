<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coa;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        // Lebih aman dari truncate (tidak bentrok FK)
        DB::table('coa')->delete();

        Coa::insert([
            [
                'id_coa' => '5-100',
                'nama_akun' => 'Beban Operasional',
                'pos_saldo' => 'Debit',
                'pos_laporan' => 'Laporan Aktivitas',
            ],
            [
                'id_coa' => '5-200',
                'nama_akun' => 'Beban Program',
                'pos_saldo' => 'Debit',
                'pos_laporan' => 'Laporan Aktivitas',
            ],
            [
                'id_coa' => '4-100',
                'nama_akun' => 'Penerimaan Donasi',
                'pos_saldo' => 'Kredit',
                'pos_laporan' => 'Laporan Aktivitas',
            ],
            [
                'id_coa' => '1-100',
                'nama_akun' => 'Kas UDD',
                'pos_saldo' => 'Debit',
                'pos_laporan' => 'Neraca',
            ],
        ]);
    }
}
