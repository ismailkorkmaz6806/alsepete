<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        $price = fake()->randomFloat(2, 50, 5000);
        $hasDiscount = fake()->boolean(40);
        $discountPrice = $hasDiscount ? round($price * 0.85, 2) : null;

        return [
            'product_id' => Product::factory(),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-###??##')),
            'color' => fake()->randomElement(['Siyah', 'Beyaz', 'Mavi', 'Kırmızı', 'Gri', 'Yeşil']),
            'size' => fake()->randomElement(['S', 'M', 'L', 'XL', 'Standard', '128GB', '256GB']),
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => fake()->numberBetween(5, 100),
        ];
    }
}
