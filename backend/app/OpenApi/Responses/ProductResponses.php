<?php

namespace App\OpenApi\Responses;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ProductResource',
    description: 'Envelope com um único produto.',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/ApiSuccess'),
        new OA\Schema(properties: [
            new OA\Property(property: 'data', ref: '#/components/schemas/Product'),
        ]),
    ],
)]
#[OA\Schema(
    schema: 'ProductCollection',
    description: 'Envelope com lista paginada de produtos.',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/ApiSuccess'),
        new OA\Schema(properties: [
            new OA\Property(
                property: 'data',
                type: 'array',
                items: new OA\Items(ref: '#/components/schemas/Product'),
            ),
            new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
        ]),
    ],
)]
#[OA\Schema(
    schema: 'AuthSession',
    description: 'Envelope retornado pelos endpoints de login/register.',
    allOf: [
        new OA\Schema(ref: '#/components/schemas/ApiSuccess'),
        new OA\Schema(properties: [
            new OA\Property(
                property: 'data',
                properties: [
                    new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                    new OA\Property(property: 'token', type: 'string', example: '12|aBcDeFgHiJ...'),
                ],
                type: 'object',
            ),
        ]),
    ],
)]
class ProductResponses
{
}
