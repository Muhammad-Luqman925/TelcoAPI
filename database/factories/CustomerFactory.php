<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(18, 60),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'location' => $this->faker->city(),
            'occupation' => $this->faker->jobTitle(),
            'current_plan' => $this->faker->randomElement(['Basic', 'Premium', 'Family', 'Unlimited']),
            'status' => $this->faker->randomElement(['active', 'churned']),
            'join_date' => $this->faker->date(),
            'avg_data_usage' => $this->faker->randomFloat(2, 1, 50),
            'avg_call_duration' => $this->faker->randomFloat(2, 1, 300),
            'avg_sms_count' => $this->faker->numberBetween(10, 200),
            'clv_segment' => $this->faker->randomElement(['Low', 'Medium', 'High']),
        ];
    }
}
