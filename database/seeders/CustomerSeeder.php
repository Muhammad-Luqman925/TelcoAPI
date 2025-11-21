<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Kita buat 1 Customer contoh
        Customer::create([
            // --- DATA PROFIL ---
            'name' => 'Luqman Tester',
            'age' => 25,
            'gender' => 'Male',
            'location' => 'Jakarta',
            'occupation' => 'Developer',
            'status' => 'active',
            'join_date' => '2023-01-01',
            'clv_segment' => 'High Value',
            'phone_number' => '081234567890',

            // --- DATA TEKNIS (Buat ML) ---
            'plan_type' => 'Postpaid',       // Paket Pascabayar
            'avg_call_duration' => 120.5,    // Menit
            'avg_data_usage_gb' => 25.5,     // Kuota gede (25GB)
            'sms_freq' => 10,                // Jarang SMS
            
            // --- DATA BARU (Requirement ML) ---
            'device_brand' => 'Samsung',     // HP
            'monthly_spend' => 150000.00,    // Bayar 150rb sebulan
            'pct_video_usage' => 0.75,       // 75% kuota abis buat nonton
            'complaint_count' => 0,          // Gak pernah komplain
            'topup_freq' => 1,               // Sekali bayar (karena postpaid)
            'travel_score' => 0.2,           // Jarang traveling
        ]);
    }
}