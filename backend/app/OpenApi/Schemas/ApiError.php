<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiError',
    title: 'Envelope de erro',
    description: 'Resposta padrão para erros. `errors` traz detalhamento por campo em validações.',
    properties: [
        new OA\Property(property: 'data', type: 'object', nullable: true, example: null),
        new OA\Property(property: 'message', type: 'string', example: 'Os dados informados são inválidos.'),
        new OA\Property(
            property: 'errors',
            type: 'object',
            nullable: true,
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string'),
            ),
            example: [
                'name' => ['O nome é obrigatório.'],
                'price' => ['O preço deve ser maior que zero.'],
            ],
        ),
    ],
)]
class ApiError
{
}
