<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_order_id')->constrained('sub_orders')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name'); // Kural 2: Sipariş anındaki ürün adı kopyası
            $table->decimal('price', 10, 2); // Kural 1 & 2: Sipariş anındaki birim fiyat kopyası
            $table->unsignedInteger('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
