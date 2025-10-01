<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir', 'guard_name' => 'web']);

        // Buat permissions berdasarkan policy files
        $permissions = [
            // Expenses permissions
            'view_any_expenses::category', 'view_expenses::category', 'create_expenses::category', 'update_expenses::category', 'delete_expenses::category', 'delete_any_expenses::category', 'force_delete_expenses::category', 'force_delete_any_expenses::category', 'restore_expenses::category', 'restore_any_expenses::category', 'replicate_expenses::category', 'reorder_expenses::category',
            'view_any_expenses::expense', 'view_expenses::expense', 'create_expenses::expense', 'update_expenses::expense', 'delete_expenses::expense', 'delete_any_expenses::expense', 'force_delete_expenses::expense', 'force_delete_any_expenses::expense', 'restore_expenses::expense', 'restore_any_expenses::expense', 'replicate_expenses::expense', 'reorder_expenses::expense',

            // Parties permissions
            'view_any_parties::customer', 'view_parties::customer', 'create_parties::customer', 'update_parties::customer', 'delete_parties::customer', 'delete_any_parties::customer', 'force_delete_parties::customer', 'force_delete_any_parties::customer', 'restore_parties::customer', 'restore_any_parties::customer', 'replicate_parties::customer', 'reorder_parties::customer',
            'view_any_parties::suppliers', 'view_parties::suppliers', 'create_parties::suppliers', 'update_parties::suppliers', 'delete_parties::suppliers', 'delete_any_parties::suppliers', 'force_delete_parties::suppliers', 'force_delete_any_parties::suppliers', 'restore_parties::suppliers', 'restore_any_parties::suppliers', 'replicate_parties::suppliers', 'reorder_parties::suppliers',

            // Products permissions
            'view_any_products::category', 'view_products::category', 'create_products::category', 'update_products::category', 'delete_products::category', 'delete_any_products::category', 'force_delete_products::category', 'force_delete_any_products::category', 'restore_products::category', 'restore_any_products::category', 'replicate_products::category', 'reorder_products::category',
            'view_any_products::product', 'view_products::product', 'create_products::product', 'update_products::product', 'delete_products::product', 'delete_any_products::product', 'force_delete_products::product', 'force_delete_any_products::product', 'restore_products::product', 'restore_any_products::product', 'replicate_products::product', 'reorder_products::product',

            // Purchases permissions
            'view_any_purchases::payment', 'view_purchases::payment', 'create_purchases::payment', 'update_purchases::payment', 'delete_purchases::payment', 'delete_any_purchases::payment', 'force_delete_purchases::payment', 'force_delete_any_purchases::payment', 'restore_purchases::payment', 'restore_any_purchases::payment', 'replicate_purchases::payment', 'reorder_purchases::payment',
            'view_any_purchases::purchase', 'view_purchases::purchase', 'create_purchases::purchase', 'update_purchases::purchase', 'delete_purchases::purchase', 'delete_any_purchases::purchase', 'force_delete_purchases::purchase', 'force_delete_any_purchases::purchase', 'restore_purchases::purchase', 'restore_any_purchases::purchase', 'replicate_purchases::purchase', 'reorder_purchases::purchase',
            'view_any_purchases::payment::return', 'view_purchases::payment::return', 'create_purchases::payment::return', 'update_purchases::payment::return', 'delete_purchases::payment::return', 'delete_any_purchases::payment::return', 'force_delete_purchases::payment::return', 'force_delete_any_purchases::payment::return', 'restore_purchases::payment::return', 'restore_any_purchases::payment::return', 'replicate_purchases::payment::return', 'reorder_purchases::payment::return',
            'view_any_purchases::purchase::return', 'view_purchases::purchase::return', 'create_purchases::purchase::return', 'update_purchases::purchase::return', 'delete_purchases::purchase::return', 'delete_any_purchases::purchase::return', 'force_delete_purchases::purchase::return', 'force_delete_any_purchases::purchase::return', 'restore_purchases::purchase::return', 'restore_any_purchases::purchase::return', 'replicate_purchases::purchase::return', 'reorder_purchases::purchase::return',

            // Sales permissions
            'view_any_sales::sale::payment', 'view_sales::sale::payment', 'create_sales::sale::payment', 'update_sales::sale::payment', 'delete_sales::sale::payment', 'delete_any_sales::sale::payment', 'force_delete_sales::sale::payment', 'force_delete_any_sales::sale::payment', 'restore_sales::sale::payment', 'restore_any_sales::sale::payment', 'replicate_sales::sale::payment', 'reorder_sales::sale::payment',
            'view_any_sales::sale', 'view_sales::sale', 'create_sales::sale', 'update_sales::sale', 'delete_sales::sale', 'delete_any_sales::sale', 'force_delete_sales::sale', 'force_delete_any_sales::sale', 'restore_sales::sale', 'restore_any_sales::sale', 'replicate_sales::sale', 'reorder_sales::sale',
            'view_any_sales::sale::return::payment', 'view_sales::sale::return::payment', 'create_sales::sale::return::payment', 'update_sales::sale::return::payment', 'delete_sales::sale::return::payment', 'delete_any_sales::sale::return::payment', 'force_delete_sales::sale::return::payment', 'force_delete_any_sales::sale::return::payment', 'restore_sales::sale::return::payment', 'restore_any_sales::sale::return::payment', 'replicate_sales::sale::return::payment', 'reorder_sales::sale::return::payment',
            'view_any_sales::sale::return', 'view_sales::sale::return', 'create_sales::sale::return', 'update_sales::sale::return', 'delete_sales::sale::return', 'delete_any_sales::sale::return', 'force_delete_sales::sale::return', 'force_delete_any_sales::sale::return', 'restore_sales::sale::return', 'restore_any_sales::sale::return', 'replicate_sales::sale::return', 'reorder_sales::sale::return',

            // Settings permissions
            'view_any_settings::currencies', 'view_settings::currencies', 'create_settings::currencies', 'update_settings::currencies', 'delete_settings::currencies', 'delete_any_settings::currencies', 'force_delete_settings::currencies', 'force_delete_any_settings::currencies', 'restore_settings::currencies', 'restore_any_settings::currencies', 'replicate_settings::currencies', 'reorder_settings::currencies',
            'view_any_settings::units', 'view_settings::units', 'create_settings::units', 'update_settings::units', 'delete_settings::units', 'delete_any_settings::units', 'force_delete_settings::units', 'force_delete_any_settings::units', 'restore_settings::units', 'restore_any_settings::units', 'replicate_settings::units', 'reorder_settings::units',

            // Users permissions
            'view_any_users::role', 'view_users::role', 'create_users::role', 'update_users::role', 'delete_users::role', 'delete_any_users::role',
            'view_any_users::user', 'view_users::user', 'create_users::user', 'update_users::user', 'delete_users::user', 'delete_any_users::user', 'force_delete_users::user', 'force_delete_any_users::user', 'restore_users::user', 'restore_any_users::user', 'replicate_users::user', 'reorder_users::user',

            // Stock Adjustment permissions
            'view_any_products::adjustment', 'view_products::adjustment', 'create_products::adjustment', 'update_products::adjustment', 'delete_products::adjustment', 'force_delete_products::adjustment', 'force_delete_any_products::adjustment',

            // Outlets permissions
            'view_any_outlets::outlet', 'view_outlets::outlet', 'create_outlets::outlet', 'update_outlets::outlet', 'delete_outlets::outlet','delete_any_outlets::outlet', 'delete_any_outlets::outlet', 'force_delete_outlets::outlet', 'force_delete_any_outlets::outlet'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign semua permissions ke owner
        $ownerRole->givePermissionTo(Permission::all());

        // Assign permissions untuk manager (hampir semua kecuali user management dan beberapa sensitive operations)
        $managerPermissions = [
            // Expenses - semua operasi
            'view_any_expenses::category', 'view_expenses::category', 'create_expenses::category', 'update_expenses::category', 'delete_expenses::category', 'delete_any_expenses::category', 'restore_expenses::category', 'restore_any_expenses::category', 'replicate_expenses::category', 'reorder_expenses::category',
            'view_any_expenses::expense', 'view_expenses::expense', 'create_expenses::expense', 'update_expenses::expense', 'delete_expenses::expense', 'delete_any_expenses::expense', 'restore_expenses::expense', 'restore_any_expenses::expense', 'replicate_expenses::expense', 'reorder_expenses::expense',

            // Parties - semua operasi
            'view_any_parties::customer', 'view_parties::customer', 'create_parties::customer', 'update_parties::customer', 'delete_parties::customer', 'delete_any_parties::customer', 'restore_parties::customer', 'restore_any_parties::customer', 'replicate_parties::customer', 'reorder_parties::customer',
            'view_any_parties::suppliers', 'view_parties::suppliers', 'create_parties::suppliers', 'update_parties::suppliers', 'delete_parties::suppliers', 'delete_any_parties::suppliers', 'restore_parties::suppliers', 'restore_any_parties::suppliers', 'replicate_parties::suppliers', 'reorder_parties::suppliers',

            // Products - semua operasi
            'view_any_products::category', 'view_products::category', 'create_products::category', 'update_products::category', 'delete_products::category', 'delete_any_products::category', 'restore_products::category', 'restore_any_products::category', 'replicate_products::category', 'reorder_products::category',
            'view_any_products::product', 'view_products::product', 'create_products::product', 'update_products::product', 'delete_products::product', 'delete_any_products::product', 'restore_products::product', 'restore_any_products::product', 'replicate_products::product', 'reorder_products::product',

            // Purchases - semua operasi
            'view_any_purchases::payment', 'view_purchases::payment', 'create_purchases::payment', 'update_purchases::payment', 'delete_purchases::payment', 'delete_any_purchases::payment', 'restore_purchases::payment', 'restore_any_purchases::payment', 'replicate_purchases::payment', 'reorder_purchases::payment',
            'view_any_purchases::purchase', 'view_purchases::purchase', 'create_purchases::purchase', 'update_purchases::purchase', 'delete_purchases::purchase', 'delete_any_purchases::purchase', 'restore_purchases::purchase', 'restore_any_purchases::purchase', 'replicate_purchases::purchase', 'reorder_purchases::purchase',
            'view_any_purchases::payment::return', 'view_purchases::payment::return', 'create_purchases::payment::return', 'update_purchases::payment::return', 'delete_purchases::payment::return', 'delete_any_purchases::payment::return', 'restore_purchases::payment::return', 'restore_any_purchases::payment::return', 'replicate_purchases::payment::return', 'reorder_purchases::payment::return',
            'view_any_purchases::purchase::return', 'view_purchases::purchase::return', 'create_purchases::purchase::return', 'update_purchases::purchase::return', 'delete_purchases::purchase::return', 'delete_any_purchases::purchase::return', 'restore_purchases::purchase::return', 'restore_any_purchases::purchase::return', 'replicate_purchases::purchase::return', 'reorder_purchases::purchase::return',

            // Sales - semua operasi
            'view_any_sales::sale::payment', 'view_sales::sale::payment', 'create_sales::sale::payment', 'update_sales::sale::payment', 'delete_sales::sale::payment', 'delete_any_sales::sale::payment', 'restore_sales::sale::payment', 'restore_any_sales::sale::payment', 'replicate_sales::sale::payment', 'reorder_sales::sale::payment',
            'view_any_sales::sale', 'view_sales::sale', 'create_sales::sale', 'update_sales::sale', 'delete_sales::sale', 'delete_any_sales::sale', 'restore_sales::sale', 'restore_any_sales::sale', 'replicate_sales::sale', 'reorder_sales::sale',
            'view_any_sales::sale::return::payment', 'view_sales::sale::return::payment', 'create_sales::sale::return::payment', 'update_sales::sale::return::payment', 'delete_sales::sale::return::payment', 'delete_any_sales::sale::return::payment', 'restore_sales::sale::return::payment', 'restore_any_sales::sale::return::payment', 'replicate_sales::sale::return::payment', 'reorder_sales::sale::return::payment',
            'view_any_sales::sale::return', 'view_sales::sale::return', 'create_sales::sale::return', 'update_sales::sale::return', 'delete_sales::sale::return', 'delete_any_sales::sale::return', 'restore_sales::sale::return', 'restore_any_sales::sale::return', 'replicate_sales::sale::return', 'reorder_sales::sale::return',

            // Settings - hanya view dan update
            'view_any_settings::currencies', 'view_settings::currencies', 'update_settings::currencies',
            'view_any_settings::units', 'view_settings::units', 'update_settings::units',

            // Users - hanya view
            'view_any_users::user', 'view_users::user',
        ];

        $managerRole->givePermissionTo($managerPermissions);

        // Assign permissions untuk kasir (operasional harian)
        $kasirPermissions = [
            // Products - hanya view
            'view_any_products::category', 'view_products::category',
            'view_any_products::product', 'view_products::product',

            // Parties - view dan create customer
            'view_any_parties::customer', 'view_parties::customer', 'create_parties::customer', 'update_parties::customer',

            // Sales - semua operasi penjualan
            'view_any_sales::sale::payment', 'view_sales::sale::payment', 'create_sales::sale::payment', 'update_sales::sale::payment',
            'view_any_sales::sale', 'view_sales::sale', 'create_sales::sale', 'update_sales::sale',
            'view_any_sales::sale::return::payment', 'view_sales::sale::return::payment', 'create_sales::sale::return::payment', 'update_sales::sale::return::payment',
            'view_any_sales::sale::return', 'view_sales::sale::return', 'create_sales::sale::return', 'update_sales::sale::return',
        ];

        $kasirRole->givePermissionTo($kasirPermissions);

        // Buat user owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@test.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $owner->assignRole($ownerRole);

        // Buat user manager
        $manager = User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name' => 'Manager Toko',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole($managerRole);

        // Buat user kasir
        $kasir = User::firstOrCreate(
            ['email' => 'kasir@test.com'],
            [
                'name' => 'Kasir Toko',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
            ]
        );
        $kasir->assignRole($kasirRole);
    }
}
