<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductService
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Role check (admin / staff only)
     */
    public function checkAccess($user)
    {
        if ($user->role === 'customer') {
            abort(response()->json(['message' => 'Akses ditolak.'], 403));
        }
    }

    /**
     * GET /products
     */
    public function getAllProducts(Request $request)
    {
        $this->checkAccess($request->user());
        return $this->repo->searchAndPaginate($request->search);
    }

    /**
     * POST /products
     */
    public function createProduct(Request $request)
    {
        $this->checkAccess($request->user());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        return $this->repo->create($validated);
    }

    /**
     * GET /products/{id}
     */
    public function getProduct(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $product = $this->repo->find($id);

        if (!$product) {
            abort(response()->json(['message' => 'Produk tidak ditemukan'], 404));
        }

        return $product;
    }

    /**
     * PUT /products/{id}
     */
    public function updateProduct(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $product = $this->repo->find($id);
        if (!$product) {
            abort(response()->json(['message' => 'Produk tidak ditemukan'], 404));
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        return $this->repo->update($product, $validated);
    }

    /**
     * DELETE /products/{id}
     */
    public function deleteProduct(Request $request, $id)
    {
        $this->checkAccess($request->user());

        $product = $this->repo->find($id);
        if (!$product) {
            abort(response()->json(['message' => 'Produk tidak ditemukan'], 404));
        }

        return $this->repo->delete($product);
    }
}
