<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        $cities = ['İstanbul', 'Ankara', 'İzmir', 'Bursa', 'Antalya', 'Kocaeli', 'Eskişehir'];
        $districts = ['Kadıköy', 'Çankaya', 'Konak', 'Nilüfer', 'Muratpaşa', 'İzmit', 'Tepebaşı'];

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement(['Ev', 'İş Yeri', 'Yazlık']),
            'city' => fake()->randomElement($cities),
            'district' => fake()->randomElement($districts),
            'full_address' => fake('tr_TR')->streetAddress() . ' No:' . fake()->buildingNumber(),
            'is_default' => false,
        ];
    }
}
