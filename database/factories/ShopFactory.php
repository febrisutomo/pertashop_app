<?php

namespace Database\Factories;

use App\Models\Corporation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->city(),
            'kode' => fake()->randomNumber(9),
            'alamat' => fake()->address(),
            'corporation_id' => Corporation::inRandomOrder()->first()->id,
            'totalisator_akhir' => fake()->randomFloat(3, 100000, 300000),
            'stik_akhir' => 142.85
        ];
    }
}
