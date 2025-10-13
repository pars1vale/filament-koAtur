<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'outlet_id' => 1,
                'category_id' => 1, // Electronics
                'product_name' => 'Laptop ASUS X441NA',
                'product_code' => 'PRD-001',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 50,
                'product_cost' => 6000000,
                'product_price' => 7500000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 5,
                'product_order_tax' => 10,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Laptop ASUS dengan processor Intel Celeron',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 1, // Electronics
                'product_name' => 'Smartphone Samsung A14',
                'product_code' => 'PRD-002',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 100,
                'product_cost' => 1500000,
                'product_price' => 2000000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 10,
                'product_order_tax' => 11,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Smartphone Samsung seri A14 4GB/64GB',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 2, // Furniture
                'product_name' => 'Kursi Kantor Ergonomis',
                'product_code' => 'PRD-003',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 25,
                'product_cost' => 450000,
                'product_price' => 650000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 3,
                'product_order_tax' => 10,
                'product_tax_type' => 0, // Exclusive
                'product_note' => 'Kursi kantor ergonomis dengan sandaran punggung yang nyaman',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 2, // Furniture
                'product_name' => 'Meja Kerja Minimalis',
                'product_code' => 'PRD-004',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 15,
                'product_cost' => 800000,
                'product_price' => 1200000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 2,
                'product_order_tax' => 10,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Meja kerja minimalis dengan material kayu jati',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 1, // Electronics
                'product_name' => 'Mouse Wireless Logitech',
                'product_code' => 'PRD-005',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 200,
                'product_cost' => 75000,
                'product_price' => 120000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 20,
                'product_order_tax' => 11,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Mouse wireless Logitech M170 dengan battery tahan lama',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 1, // Electronics
                'product_name' => 'Keyboard Mechanical RGB',
                'product_code' => 'PRD-006',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 30,
                'product_cost' => 350000,
                'product_price' => 500000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 5,
                'product_order_tax' => 10,
                'product_tax_type' => 0, // Exclusive
                'product_note' => 'Keyboard mechanical dengan backlight RGB dan switch blue',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 2, // Furniture
                'product_name' => 'Rak Buku 3 Susun',
                'product_code' => 'PRD-007',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 40,
                'product_cost' => 250000,
                'product_price' => 380000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 5,
                'product_order_tax' => 10,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Rak buku 3 susun dengan material kayu MDF',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'outlet_id' => 1,
                'category_id' => 1, // Electronics
                'product_name' => 'Monitor 24 Inch LED',
                'product_code' => 'PRD-008',
                'product_barcode_symbology' => 'EAN-13',
                'product_quantity' => 20,
                'product_cost' => 1200000,
                'product_price' => 1650000,
                'unit_id' => 1, // Piece
                'product_stock_alert' => 3,
                'product_order_tax' => 11,
                'product_tax_type' => 1, // Inclusive
                'product_note' => 'Monitor LED 24 inch dengan resolusi Full HD',
                'product_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
