<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('sellers')->cascadeOnDelete();
            $table->enum('status', [
                'onay_bekliyor',
                'hazirlaniyor',
                'kargoya_verildi',
                'teslim_edildi',
                'iptal',
                'iade',
            ])->default('onay_bekliyor')->index();
            $table->string('cargo_company')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->decimal('commission_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_orders');
    }
};
