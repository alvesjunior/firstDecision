<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

class AuthPaths
{
    #[OA\Post(
        path: '/api/auth/register',
        tags: ['Auth'],
        summary: 'Registra um novo usuário e retorna um token Sanctum',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest'),
        ),
        responses: [
            new OA\Response(response: 201, description: 'Usuário criado', content: new OA\JsonContent(ref: '#/components/schemas/AuthSession')),
            new OA\Response(response: 422, description: 'Erro de validação', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function register(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/login',
        tags: ['Auth'],
        summary: 'Autentica e retorna um token Sanctum',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest'),
        ),
        responses: [
            new OA\Response(response: 200, description: 'Autenticado', content: new OA\JsonContent(ref: '#/components/schemas/AuthSession')),
            new OA\Response(response: 422, description: 'Credenciais inválidas', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function login(): void
    {
    }

    #[OA\Get(
        path: '/api/auth/me',
        tags: ['Auth'],
        summary: 'Retorna o usuário autenticado',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Usuário autenticado',
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: '#/components/schemas/ApiSuccess'),
                        new OA\Schema(properties: [new OA\Property(property: 'data', ref: '#/components/schemas/User')]),
                    ],
                ),
            ),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function me(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/logout',
        tags: ['Auth'],
        summary: 'Revoga o token atual',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Sessão encerrada', content: new OA\JsonContent(ref: '#/components/schemas/ApiSuccess')),
            new OA\Response(response: 401, description: 'Não autenticado', content: new OA\JsonContent(ref: '#/components/schemas/ApiError')),
        ],
    )]
    public function logout(): void
    {
    }
}
