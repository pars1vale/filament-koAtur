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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_code');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('sub_total');
            $table->integer('product_discount_amount')->default(0);
            $table->string('product_discount_type')->default('fixed');
            $table->integer('product_tax_amount')->default(0);
            $table->foreign('sale_id')->nullable()->references('id')
                ->on('sales')->cascadeOnDelete();
            $table->foreign('product_id')->references('id')
                ->on('products')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
