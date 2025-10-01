<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->cascadeOnDelete();
            $table->unsignedBigInteger('category_id');
            $table->string('product_name');
            $table->string('product_code')->unique();
            $table->string('product_barcode_symbology')->default('EAN-13');
            $table->integer('product_quantity');
            $table->integer('product_cost');
            $table->integer('product_price');
            $table->unsignedBigInteger('unit_id'); // relasi ke tabel units
            $table->integer('product_stock_alert')->default(0);
            $table->integer('product_order_tax')->nullable();
            $table->tinyInteger('product_tax_type')->nullable(); // 0=Exclusive, 1=Inclusive
            $table->text('product_note')->nullable();
            $table->string('product_image')->nullable();

            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
