<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function searchAndPaginate($search, $perPage = 10)
    {
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('category', 'like', "%$search%");
        }

        return $query->paginate($perPage);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function find($id)
    {
        return Product::find($id);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
