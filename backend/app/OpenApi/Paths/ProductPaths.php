<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

class ProductPaths
{
    #[OA\Get(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Lista produtos paginados, com filtros e ordenação',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', description: 'Busca em nome e descrição', schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'min_price', in: 'query', description: 'Preço mínimo', schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\Parameter(name: 'max_price', in: 'query', description: 'Preço máximo', schema: new OA\Schema(type: 'number', format: 'float')),
            new OA\Parameter(name: 'min_stock', in: 'query', description: 'Estoque mínimo', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'max_stock', in: 'query', description: 'Estoque máximo', schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(
                name: 'sort',
                in: 'query',
                description: 'Campo de ordenação',
                schema: new OA\Schema(type: 'string', enum: ['name', 'price', 'stock', 'created_at']),
            ),
            new OA\Parameter(
                name: 'direction',
                in: 'query',
                schema: new OA\Schema(type: 'string', enum: ['asc', 'desc'], default: 'asc'),
            ),
            new OA\Parameter(name: 'per_page', in: 'query', description: '1 a 100', schema: new OA\Schema(type: 'integer', minimum: 1, maximum: 100, default: 15)),
            new OA\Parameter(name: 'page', in: 'query', schema: new OA\Schema(type: 'integer', minimum: 1, default: 1)),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Lista paginada', content: new OA\JsonContent(ref: '#/components/schemas/ProductCollection')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function index(): void
    {
    }

    #[OA\Post(
        path: '/api/products',
        tags: ['Products'],
        summary: 'Cria um novo produto',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreProductRequest'),
        ),
        responses: [
            new OA\Response(response: 201, description: 'Criado', content: new OA\JsonContent(ref: '#/components/schemas/ProductResource')),
            new OA\Response(response: 422, description: 'Erro de validação', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function store(): void
    {
    }

    #[OA\Get(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Retorna detalhes de um produto',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Produto encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ProductResource')),
            new OA\Response(response: 404, description: 'Não encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function show(): void
    {
    }

    #[OA\Put(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Atualiza um produto (PATCH também aceito)',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateProductRequest'),
        ),
        responses: [
            new OA\Response(response: 200, description: 'Atualizado', content: new OA\JsonContent(ref: '#/components/schemas/ProductResource')),
            new OA\Response(response: 422, description: 'Erro de validação', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 404, description: 'Não encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function update(): void
    {
    }

    #[OA\Delete(
        path: '/api/products/{product}',
        tags: ['Products'],
        summary: 'Remove um produto',
        security: [['sanctum' => []]],
        parameters: [
            new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Removido', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
            new OA\Response(response: 404, description: 'Não encontrado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function destroy(): void
    {
    }
}
