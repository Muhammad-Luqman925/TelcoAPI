<?php

namespace Database\Factories;

use App\Models\Recommendation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecommendationItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'recommendation_id' => Recommendation::factory(),
            'product_id' => Product::factory(),
            'score' => $this->faker->randomFloat(2, 0.4, 1.0),
            'rank' => $this->faker->numberBetween(1, 5),
        ];
    }
}
