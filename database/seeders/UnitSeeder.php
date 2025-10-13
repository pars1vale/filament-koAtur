<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            [
                'outlet_id' => '1',
                'name' => 'Piece',
                'short_name' => 'pc',
                'operator' => '*',
                'operation_value' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => '1',
                'name' => 'Box',
                'short_name' => 'box',
                'operator' => '*',
                'operation_value' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
