<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'supplier_name' => 'PT Maju Jaya',
                'supplier_email' => 'majujaya@example.com',
                'supplier_phone' => '08123456789',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'address' => 'Jl. Sudirman No. 1, Jakarta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_name' => 'CV Sumber Makmur',
                'supplier_email' => 'sumbermakmur@example.com',
                'supplier_phone' => '082233445566',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'address' => 'Jl. Asia Afrika No. 10, Bandung',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
