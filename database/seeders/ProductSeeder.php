<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Daftar Produk RESMI dari Output ML
        // Harganya saya karang dulu ya, nanti Luqman bisa sesuaikan
        $products = [
            [
                'name' => 'Voice Bundle', // <--- INI YANG PENTING (Harus sama persis)
                'price' => 50000, 
                'category' => 'Voice',
                'description' => 'Paket nelpon puas seharian.'
            ],
            [
                'name' => 'Top-up Promo',
                'price' => 25000, 
                'category' => 'Promo',
                'description' => 'Bonus kuota setiap isi ulang.'
            ],
            [
                'name' => 'Streaming Partner Pack',
                'price' => 75000, 
                'category' => 'Streaming',
                'description' => 'Nonton Netflix & YouTube tanpa kuota utama.'
            ],
            [
                'name' => 'Roaming Pass',
                'price' => 150000, 
                'category' => 'Roaming',
                'description' => 'Internet lancar di luar negeri.'
            ],
            [
                'name' => 'Retention Offer',
                'price' => 10000, 
                'category' => 'Special',
                'description' => 'Penawaran spesial biar gak pindah provider.'
            ],
            // Tambahan produk lain kalau ada
            [
                'name' => 'Data Pack 50GB',
                'price' => 100000, 
                'category' => 'Data',
                'description' => 'Kuota besar untuk internetan.'
            ]
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}