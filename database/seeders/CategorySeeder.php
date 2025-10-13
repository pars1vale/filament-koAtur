<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'outlet_id' => '1',
                'category_code' => 'CAT-001',
                'category_name' => 'Electronics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => '1',
                'category_code' => 'CAT-002',
                'category_name' => 'Furniture',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
