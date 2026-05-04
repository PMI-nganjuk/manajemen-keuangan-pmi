<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccounts;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('chart_of_accounts')->delete();

        ChartOfAccounts::insert([
            [
                'id' => '5-100',
                'category_two' => 11,
                'account_name' => 'Beban Operasional',
                'entry_type' => 'D',
                'report_type_id' => 2,
            ],
            [
                'id' => '5-200',
                'category_two' => 12,
                'account_name' => 'Beban Program',
                'entry_type' => 'D',
                'report_type_id' => 2,
            ],
            [
                'id' => '4-100',
                'category_two' => 9,
                'account_name' => 'Penerimaan Donasi',
                'entry_type' => 'K',
                'report_type_id' => 1,
            ],
            [
                'id' => '1-100',
                'category_two' => 1,
                'account_name' => 'Kas UDD',
                'entry_type' => 'D',
                'report_type_id' => 2,
            ],
        ]);
    }
}