<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignOutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = Outlet::all();
        $outletPusat = Outlet::where('slug', 'outlet-pusat')->first();

        // Owner - semua outlet
        User::where('email', 'owner@test.com')->first()?->outlets()->sync($outlets->pluck('id'));

        // Manager & Kasir - outlet pusat saja
        if ($outletPusat) {
            User::where('email', 'manager@test.com')->first()?->outlets()->sync([$outletPusat->id]);
            User::where('email', 'kasir@test.com')->first()?->outlets()->sync([$outletPusat->id]);
        }
    }
}
