<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin (Full Akses)
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@telco.com',
            'password' => Hash::make('password123'), // Jangan lupa di-hash
            'role'     => 'admin', // Sesuai enum di migration kamu
        ]);

        // 2. Akun Marketing Staff (Buat demo generate rekomendasi)
        User::create([
            'name'     => 'Marketing Staff',
            'email'    => 'staff@telco.com',
            'password' => Hash::make('password123'),
            'role'     => 'staff', // Sesuai enum di migration kamu
        ]);
    }
}