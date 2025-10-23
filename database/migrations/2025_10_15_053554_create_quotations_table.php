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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('outlet_id')->constrained('outlets')->cascadeOnDelete();
            $table->date('date');
            $table->string('reference')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->string('customer_name');
            $table->float('tax_percentage')->default(0);
            $table->float('tax_amount')->default(0);
            $table->float('discount_percentage')->default(0);
            $table->float('discount_amount')->default(0);
            $table->float('shipping_amount')->default(0);
            $table->float('total_amount')->default(0);
            $table->enum('status', ['Pending', 'Sent'])->default('Pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
