<?php

namespace App\Services;

use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;

class TransactionService
{
    protected $repo;

    public function __construct(TransactionRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Only admin + staff can access
     */
    public function checkAccess($user)
    {
        if (!in_array($user->role, ['admin', 'staff'])) {
            abort(response()->json(['message' => 'Akses ditolak.'], 403));
        }
    }

    public function getAll(Request $request)
    {
        $this->checkAccess($request->user());

        return $this->repo->searchAndPaginate(
            $request->search,
            $request->type,
            $request->channel
        );
    }

    public function store(Request $request)
    {
        $this->checkAccess($request->user());

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:purchase,renewal,topup',
            'channel' => 'nullable|string',
            'transaction_date' => 'nullable|date',
        ]);

        return $this->repo->create($validated);
    }

    public function show(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $trx = $this->repo->find($id);
        if (!$trx) {
            abort(response()->json(['message' => 'Transaksi tidak ditemukan'], 404));
        }

        return $trx;
    }

    public function update(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $trx = $this->repo->find($id);
        if (!$trx) {
            abort(response()->json(['message' => 'Transaksi tidak ditemukan'], 404));
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric|min:0',
            'type' => 'required|string|in:purchase,renewal,topup',
            'channel' => 'nullable|string',
            'transaction_date' => 'nullable|date',
        ]);

        return $this->repo->update($trx, $validated);
    }

    public function delete(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $trx = $this->repo->find($id);
        if (!$trx) {
            abort(response()->json(['message' => 'Transaksi tidak ditemukan'], 404));
        }

        return $this->repo->delete($trx);
    }
}
