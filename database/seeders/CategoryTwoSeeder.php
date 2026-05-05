<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryTwo;

class CategoryTwoSeeder extends Seeder
{
    public function run(): void
    {
        CategoryTwo::truncate();

        CategoryTwo::insert([
            ['category_name' => 'Kas', 'category_one' => 1],
            ['category_name' => 'Bank', 'category_one' => 1],
            ['category_name' => 'Piutang', 'category_one' => 1],
            ['category_name' => 'Inventaris', 'category_one' => 1],
            ['category_name' => 'Hutang Usaha', 'category_one' => 2],
            ['category_name' => 'Hutang Lainnya', 'category_one' => 2],
            ['category_name' => 'Modal Awal', 'category_one' => 3],
            ['category_name' => 'Laba Ditahan', 'category_one' => 3],
            ['category_name' => 'Donasi', 'category_one' => 4],
            ['category_name' => 'Penerimaan Lainnya', 'category_one' => 4],
            ['category_name' => 'Beban Operasional', 'category_one' => 5],
            ['category_name' => 'Beban Program', 'category_one' => 5],
        ]);
    }
}
