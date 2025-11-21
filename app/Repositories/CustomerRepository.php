<?php

namespace App\Repositories;

use App\Models\Customer;

class CustomerRepository
{
    /**
     * Ambil semua customer dengan search & filter status & pagination.
     */
    public function searchAndPaginate($search, $status, $perPage = 10)
    {
        $query = Customer::query();

        // Search (Berdasarkan Nama atau ID sesuai URS)
        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('id', $search); // URS minta search by ID atau Name
        }

        // Filter status
        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    /**
     * Ambil Detail Customer + 5 Transaksi Terakhir
     * Sesuai Requirement URQ-004
     */
    public function find($id)
    {
        // Kita gunakan 'with' untuk Eager Loading relasi transactions
        return Customer::with(['transactions' => function($query) {
            $query->latest() // Urutkan dari yang terbaru
                  ->take(5); // Ambil cuma 5 biji
        }])->findOrFail($id); // Pakai findOrFail biar kalau ID ga ada, otomatis 404
    }

    public function update(Customer $customer, array $data)
    {
        $customer->update($data);
        return $customer;
    }

    public function delete(Customer $customer)
    {
        return $customer->delete();
    }
}