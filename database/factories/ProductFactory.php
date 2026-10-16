<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake('tr_TR')->sentence(3);
        return [
            'seller_id' => Seller::factory(),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => rtrim($name, '.'),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1000, 999999),
            'description' => fake('tr_TR')->paragraphs(3, true),
            'status' => 'aktif',
        ];
    }
}
