<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'firstDecision API',
    description: 'API REST para gerenciamento de produtos. Todas as respostas seguem o envelope `{ data, message, errors }`, com `meta` em endpoints paginados.',
    contact: new OA\Contact(name: 'firstDecision', email: 'contato@firstdecision.test'),
)]
#[OA\Server(url: 'http://localhost:8000', description: 'Ambiente local (Docker)')]
#[OA\Server(url: '/', description: 'Mesmo host da SPA')]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum personal access token',
    description: 'Use o token retornado pelos endpoints de login/register no formato `Bearer {token}`.',
)]
#[OA\Tag(name: 'Auth', description: 'Autenticação via Laravel Sanctum')]
#[OA\Tag(name: 'Products', description: 'CRUD de produtos (rotas protegidas)')]
class OpenApiSpec
{
}
