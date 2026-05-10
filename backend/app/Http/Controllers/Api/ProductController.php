<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\ProductFilters;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly ProductService $service)
    {
    }

    public function index(Request $request, ProductFilters $filters): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 15);
        $paginator = $this->service->list($filters, $perPage);

        return $this->success(new ProductCollection($paginator), 'Lista de produtos.');
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->service->create($request->validated());

        return $this->created(new ProductResource($product), 'Produto criado com sucesso.');
    }

    public function show(int $product): JsonResponse
    {
        $found = $this->service->find($product);

        return $this->success(new ProductResource($found), 'Produto encontrado.');
    }

    public function update(UpdateProductRequest $request, int $product): JsonResponse
    {
        $updated = $this->service->update($product, $request->validated());

        return $this->success(new ProductResource($updated), 'Produto atualizado com sucesso.');
    }

    public function destroy(int $product): JsonResponse
    {
        $this->service->delete($product);

        return $this->noContent('Produto removido com sucesso.');
    }
}
