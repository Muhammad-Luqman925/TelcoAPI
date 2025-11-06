<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecommendationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(),
            'recommended_by' => User::inRandomOrder()->value('id') ?? User::factory(),
            'is_overridden' => $this->faker->boolean(10), // 10% kemungkinan override
            'override_product_id' => null,
        ];
    }

    /**
     * Setelah rekomendasi dibuat, tambahkan 3 item produk acak.
     */
    public function configure()
    {
        return $this->afterCreating(function ($recommendation) {
            $products = Product::inRandomOrder()->take(3)->get();
            $rank = 1;

            foreach ($products as $product) {
                $recommendation->items()->create([
                    'product_id' => $product->id,
                    'score' => fake()->randomFloat(2, 0.5, 1.0),
                    'rank' => $rank++,
                ]);
            }
        });
    }
}
