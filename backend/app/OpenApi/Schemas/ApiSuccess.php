<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiSuccess',
    title: 'Envelope de sucesso',
    description: 'Estrutura padrão de resposta de sucesso (`data` é um objeto ou array).',
    properties: [
        new OA\Property(property: 'data', type: 'object', nullable: true),
        new OA\Property(property: 'message', type: 'string', nullable: true, example: 'Operação realizada com sucesso.'),
        new OA\Property(property: 'errors', type: 'object', nullable: true, example: null),
    ],
)]
class ApiSuccess
{
}
