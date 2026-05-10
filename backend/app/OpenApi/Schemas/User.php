<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'User',
    title: 'Usuário',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Administrador'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@firstdecision.test'),
    ],
)]
class User
{
}
