<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ApiResponse;

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
        ]);

        $token = $user->createToken($request->input('device_name', 'web'))->plainTextToken;

        return $this->created(
            [
                'user' => $user->only(['id', 'name', 'email']),
                'token' => $token,
            ],
            'Usuário registrado com sucesso.',
        );
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->string('email')->toString())->first();

        if (! $user || ! Hash::check($request->string('password')->toString(), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais inválidas.'],
            ]);
        }

        $token = $user->createToken($request->input('device_name', 'web'))->plainTextToken;

        return $this->success(
            [
                'user' => $user->only(['id', 'name', 'email']),
                'token' => $token,
            ],
            'Autenticado com sucesso.',
        );
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user()->only(['id', 'name', 'email']));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Sessão encerrada.', Response::HTTP_OK);
    }
}
