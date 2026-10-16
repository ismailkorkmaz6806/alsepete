<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->string('period')->index(); // örn: 2026-10
            $table->decimal('gross', 10, 2); // Brüt satış tutarı
            $table->decimal('commission', 10, 2); // Kesilen komisyon tutarı
            $table->decimal('net', 10, 2); // Satıcıya ödenecek net tutar
            $table->timestamp('paid_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_payouts');
    }
};
