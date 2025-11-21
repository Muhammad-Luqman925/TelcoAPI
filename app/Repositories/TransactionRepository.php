<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    public function searchAndPaginate($search, $type, $channel, $perPage = 10)
    {
        $query = Transaction::with(['customer', 'product']);

        // Search by customer name
        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($channel) {
            $query->where('channel', $channel);
        }

        return $query->orderBy('transaction_date', 'desc')->paginate($perPage);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function find($id)
    {
        return Transaction::with(['customer', 'product'])->find($id);
    }

    public function update(Transaction $transaction, array $data)
    {
        $transaction->update($data);
        return $transaction;
    }

    public function delete(Transaction $transaction)
    {
        return $transaction->delete();
    }
}
