<?php

namespace App\OpenApi\RequestBodies;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'LoginRequest',
    required: ['email', 'password'],
    properties: [
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@firstdecision.test'),
        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password'),
        new OA\Property(property: 'device_name', type: 'string', example: 'web', nullable: true),
    ],
)]
#[OA\Schema(
    schema: 'RegisterRequest',
    required: ['name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'name', type: 'string', minLength: 2, example: 'Maria Silva'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'maria@test.com'),
        new OA\Property(property: 'password', type: 'string', format: 'password', minLength: 8, example: 'segredo123'),
        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'segredo123'),
    ],
)]
class AuthRequestBodies
{
}
