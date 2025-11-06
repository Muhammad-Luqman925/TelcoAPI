<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'product_id' => Product::factory(),
            'transaction_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 20000, 500000),
            'channel' => $this->faker->randomElement(['Online', 'App', 'Store']),
        ];
    }
}
