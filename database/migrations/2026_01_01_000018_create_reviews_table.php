<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5 yıldız
            $table->text('comment')->nullable();
            $table->boolean('is_approved')->default(false)->index();
            $table->timestamps();

            $table->unique(['user_id', 'order_item_id']); // Bir sipariş kalemine bir kere yorum yapılabilir
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
