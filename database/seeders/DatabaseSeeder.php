<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Recommendation;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat admin default
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@telco.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Dummy data lainnya
        User::factory(5)->create();
        Customer::factory(30)->create();
        Product::factory(10)->create();
        Transaction::factory(40)->create();
        Recommendation::factory(15)->create();
    }
}
