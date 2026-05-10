<?php

namespace App\OpenApi\RequestBodies;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreProductRequest',
    required: ['name', 'price', 'stock'],
    properties: [
        new OA\Property(property: 'name', type: 'string', minLength: 2, maxLength: 255, example: 'Cadeira Gamer Pro'),
        new OA\Property(property: 'description', type: 'string', maxLength: 5000, nullable: true, example: 'Cadeira ergonômica com apoio lombar.'),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0.01, example: 1599.90),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 25),
    ],
)]
#[OA\Schema(
    schema: 'UpdateProductRequest',
    description: 'Mesmos campos do StoreProductRequest. Todos opcionais — atualização parcial.',
    properties: [
        new OA\Property(property: 'name', type: 'string', minLength: 2, maxLength: 255, example: 'Cadeira Gamer Pro v2'),
        new OA\Property(property: 'description', type: 'string', maxLength: 5000, nullable: true),
        new OA\Property(property: 'price', type: 'number', format: 'float', minimum: 0.01, example: 1799.00),
        new OA\Property(property: 'stock', type: 'integer', minimum: 0, example: 30),
    ],
)]
class ProductRequestBodies
{
}
