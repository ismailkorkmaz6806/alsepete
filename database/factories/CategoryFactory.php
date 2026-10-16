<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->department();
        return [
            'parent_id' => null,
            'name' => $name,
            'slug' => Str::slug($name),
            'commission_rate' => fake()->randomElement([8.00, 10.00, 12.00, 15.00]),
            'sort' => fake()->numberBetween(1, 50),
        ];
    }
}
