<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('sku')->unique()->index();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('price', 10, 2); // Kural 1: Fiyat daima decimal(10,2)
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->unsignedInteger('stock')->default(0); // Kural 3: Stok varyantta ve transaction ile kilitlenir
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
