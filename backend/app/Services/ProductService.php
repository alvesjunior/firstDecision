<?php

namespace App\Services;

use App\Http\Filters\ProductFilters;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $repository)
    {
    }

    public function list(ProductFilters $filters, int $perPage = 15): LengthAwarePaginator
    {
        $perPage = max(1, min($perPage, 100));

        return $this->repository->paginate($filters, $perPage);
    }

    public function find(int $id): Product
    {
        return $this->repository->findOrFail($id);
    }

    public function create(array $attributes): Product
    {
        return $this->repository->create($attributes);
    }

    public function update(int $id, array $attributes): Product
    {
        $product = $this->repository->findOrFail($id);

        return $this->repository->update($product, $attributes);
    }

    public function delete(int $id): void
    {
        $product = $this->repository->findOrFail($id);

        $this->repository->delete($product);
    }
}
