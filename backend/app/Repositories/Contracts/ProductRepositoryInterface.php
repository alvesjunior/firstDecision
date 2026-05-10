<?php

namespace App\Repositories\Contracts;

use App\Http\Filters\ProductFilters;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginate(ProductFilters $filters, int $perPage = 15): LengthAwarePaginator;

    public function findOrFail(int $id): Product;

    public function create(array $attributes): Product;

    public function update(Product $product, array $attributes): Product;

    public function delete(Product $product): void;
}
