<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            [
                'outlet_id' => '1',
                'currency_name' => 'Indonesian Rupiah',
                'code' => 'IDR',
                'symbol' => 'Rp',
                'thousand_separator' => '.',
                'decimal_separator' => ',',
                'exchange_rate' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => '1',
                'currency_name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'thousand_separator' => ',',
                'decimal_separator' => '.',
                'exchange_rate' => 15000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
