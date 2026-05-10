<?php

namespace App\Repositories\Eloquent;

use App\Http\Filters\ProductFilters;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(ProductFilters $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query();

        $filters->apply($query);

        if (! $query->getQuery()->orders) {
            $query->latest('id');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function findOrFail(int $id): Product
    {
        return Product::query()->findOrFail($id);
    }

    public function create(array $attributes): Product
    {
        return Product::query()->create($attributes);
    }

    public function update(Product $product, array $attributes): Product
    {
        $product->fill($attributes)->save();

        return $product->refresh();
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
