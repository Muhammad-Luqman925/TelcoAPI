<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua customer & produk
        $customers = Customer::all();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->info('Customer atau Produk kosong. Jalankan CustomerSeeder & ProductSeeder dulu.');
            return;
        }

        // Buat data dummy transaksi mundur ke belakang (3 bulan terakhir)
        foreach ($customers as $customer) {
            // Setiap customer punya 5-10 transaksi acak
            $totalTrx = rand(5, 10);

            for ($i = 0; $i < $totalTrx; $i++) {
                $randomProduct = $products->random();
                
                // Random tanggal dalam 90 hari terakhir
                $date = Carbon::now()->subDays(rand(0, 90));

                Transaction::create([
                    'customer_id' => $customer->id,
                    'product_id'  => $randomProduct->id,
                    'transaction_date' => $date,
                    // Harga acak sekitar harga produk (biar variatif)
                    'amount'      => $randomProduct->price, 
                    'channel'     => fake()->randomElement(['Mobile App', 'USSD', 'Website', 'Grapari']),
                    'created_at'  => $date,
                    'updated_at'  => $date,
                ]);
            }
        }
    }
}