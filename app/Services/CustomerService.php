<?php

namespace App\Services;

use App\Repositories\CustomerRepository;
use Illuminate\Http\Request;

class CustomerService
{
    protected $repo;

    public function __construct(CustomerRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Cek role user (hanya admin & staff)
     */
    public function checkAccess($user)
    {
        if ($user->role === 'customer') {
            abort(response()->json(['message' => 'Akses ditolak.'], 403));
        }
    }

    /** GET /customers */
    public function getAll(Request $request)
    {
        $this->checkAccess($request->user());

        return $this->repo->searchAndPaginate(
            $request->search,
            $request->status
        );
    }

    /** POST /customers */
    public function store(Request $request)
    {
        $this->checkAccess($request->user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:active,churned',
            'join_date' => 'nullable|date',
        ]);

        return $this->repo->create($validated);
    }

    /** GET /customers/{id} */
    public function show(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $customer = $this->repo->find($id);
        if (!$customer) {
            abort(response()->json(['message' => 'Customer tidak ditemukan'], 404));
        }

        return $customer;
    }

    /** PUT /customers/{id} */
    public function update(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $customer = $this->repo->find($id);
        if (!$customer) {
            abort(response()->json(['message' => 'Customer tidak ditemukan'], 404));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:active,churned',
            'join_date' => 'nullable|date',
        ]);

        return $this->repo->update($customer, $validated);
    }

    /** DELETE /customers/{id} */
    public function delete(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $customer = $this->repo->find($id);
        if (!$customer) {
            abort(response()->json(['message' => 'Customer tidak ditemukan'], 404));
        }

        return $this->repo->delete($customer);
    }
}
