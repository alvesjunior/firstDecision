<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_registers_a_new_user_and_returns_a_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Maria',
            'email' => 'maria@test.com',
            'password' => 'segredo123',
            'password_confirmation' => 'segredo123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email'], 'token'], 'message', 'errors']);

        $this->assertDatabaseHas('users', ['email' => 'maria@test.com']);
    }

    #[Test]
    public function it_logs_in_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'jose@test.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.email', 'jose@test.com')
            ->assertJsonStructure(['data' => ['token']]);
    }

    #[Test]
    public function it_rejects_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'a@b.c', 'password' => Hash::make('xxx')]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'a@b.c',
            'password' => 'wrong',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['data', 'message', 'errors' => ['email']]);
    }

    #[Test]
    public function it_returns_401_for_protected_endpoint_without_token(): void
    {
        $this->getJson('/api/auth/me')->assertUnauthorized()
            ->assertJsonStructure(['data', 'message', 'errors']);
    }

    #[Test]
    public function it_returns_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);
    }
}
