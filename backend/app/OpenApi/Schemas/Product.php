<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'Product',
    title: 'Produto',
    description: 'Representação de um produto retornado pela API.',
    required: ['id', 'name', 'price', 'stock'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Cadeira Gamer Pro'),
        new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Cadeira ergonômica com apoio lombar.'),
        new OA\Property(property: 'price', type: 'number', format: 'float', example: 1599.90),
        new OA\Property(property: 'stock', type: 'integer', example: 25),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-05-10T14:30:00+00:00'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-05-10T14:30:00+00:00'),
    ],
)]
class Product
{
}
