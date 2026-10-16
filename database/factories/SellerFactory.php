<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $store = fake('tr_TR')->company() . ' Mağazası';
        return [
            'user_id' => User::factory(),
            'store_name' => $store,
            'slug' => Str::slug($store) . '-' . fake()->unique()->numberBetween(100, 999),
            'tax_number' => fake()->numerify('##########'),
            'iban' => 'TR' . fake()->numerify('########################'),
            'status' => 'onayli',
            'commission_rate' => 10.00,
        ];
    }
}
