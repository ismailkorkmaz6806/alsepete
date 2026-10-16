<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('order_number')->unique()->index();
            $table->json('address'); // Kural 2: Sipariş anındaki teslimat/fatura adresi JSON snapshot kopya
            $table->decimal('subtotal', 10, 2); // Kural 1: decimal(10,2)
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('shipping', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->enum('payment_status', ['beklemede', 'odendi', 'basarisiz', 'iptal'])->default('beklemede')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
