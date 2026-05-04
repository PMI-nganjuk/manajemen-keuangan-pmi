<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportTypes;

class ReportTypeSeeder extends Seeder
{
    public function run(): void
    {
        ReportTypes::truncate();

        ReportTypes::insert([
            ['report_name' => 'Laporan Aktivitas'],
            ['report_name' => 'Neraca'],
        ]);
    }
}
