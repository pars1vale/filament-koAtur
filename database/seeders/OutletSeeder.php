<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = [
            [
                'name' => 'Outlet Pusat',
                'slug' => 'outlet-pusat',
            ],
            [
                'name' => 'Outlet Cabang 1',
                'slug' => 'outlet-cabang-1',
            ],
            [
                'name' => 'Outlet Cabang 2',
                'slug' => 'outlet-cabang-2',
            ],
            // [
            //     'name' => 'Outlet Cabang Bali',
            //     'slug' => 'outlet-cabang-bali',
            // ],
            // [
            //     'name' => 'Outlet Cabang Medan',
            //     'slug' => 'outlet-cabang-medan',
            // ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create($outlet);
        }
    }
}
