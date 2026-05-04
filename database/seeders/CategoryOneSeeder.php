<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryOne;

class CategoryOneSeeder extends Seeder
{
    public function run(): void
    {
        CategoryOne::truncate();

        CategoryOne::insert([
            ['category_name' => 'Aset'],
            ['category_name' => 'Liabilitas'],
            ['category_name' => 'Modal'],
            ['category_name' => 'Penerimaan'],
            ['category_name' => 'Beban'],
        ]);
    }
}