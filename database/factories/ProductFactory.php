<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Plan',
            'category' => $this->faker->randomElement(['Internet', 'Voice', 'SMS', 'Combo']),
            'price' => $this->faker->randomFloat(2, 20000, 500000),
            'description' => $this->faker->sentence(),
            'benefits' => $this->faker->sentence(6),
        ];
    }
}
