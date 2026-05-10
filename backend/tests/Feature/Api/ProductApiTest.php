<?php

namespace Tests\Feature\Api;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    private function authed(): self
    {
        return $this->actingAs(User::factory()->create(), 'sanctum');
    }

    #[Test]
    public function it_requires_authentication_for_all_product_endpoints(): void
    {
        $this->getJson('/api/products')->assertUnauthorized();
        $this->postJson('/api/products', [])->assertUnauthorized();
        $this->getJson('/api/products/1')->assertUnauthorized();
        $this->putJson('/api/products/1', [])->assertUnauthorized();
        $this->deleteJson('/api/products/1')->assertUnauthorized();
    }

    #[Test]
    public function it_lists_products_with_pagination_and_meta(): void
    {
        Product::factory()->count(25)->create();

        $response = $this->authed()->getJson('/api/products?per_page=10');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [['id', 'name', 'description', 'price', 'stock', 'created_at', 'updated_at']],
                'message',
                'errors',
                'meta' => ['current_page', 'per_page', 'total', 'last_page', 'from', 'to'],
            ])
            ->assertJsonPath('meta.total', 25)
            ->assertJsonPath('meta.per_page', 10);

        $this->assertCount(10, $response->json('data'));
    }

    #[Test]
    public function it_filters_products_by_search_and_price(): void
    {
        Product::factory()->create(['name' => 'Cadeira Gamer', 'price' => 1500]);
        Product::factory()->create(['name' => 'Mesa Office', 'price' => 800]);
        Product::factory()->create(['name' => 'Outro item', 'price' => 200]);

        $response = $this->authed()->getJson('/api/products?search=mesa&min_price=500&max_price=900');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertSame('Mesa Office', $response->json('data.0.name'));
    }

    #[Test]
    public function it_creates_a_product_with_standardized_response(): void
    {
        $payload = [
            'name' => 'Notebook',
            'description' => 'Pro Max',
            'price' => 9999.99,
            'stock' => 3,
        ];

        $response = $this->authed()->postJson('/api/products', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Notebook')
            ->assertJsonPath('data.price', 9999.99)
            ->assertJsonStructure(['data' => ['id'], 'message', 'errors']);

        $this->assertDatabaseHas('products', ['name' => 'Notebook', 'stock' => 3]);
    }

    #[Test]
    public function it_validates_payload_on_create(): void
    {
        $response = $this->authed()->postJson('/api/products', [
            'name' => '',
            'price' => -1,
            'stock' => -3,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure(['data', 'message', 'errors' => ['name', 'price', 'stock']]);
    }

    #[Test]
    public function it_rejects_duplicate_product_names(): void
    {
        Product::factory()->create(['name' => 'Único']);

        $response = $this->authed()->postJson('/api/products', [
            'name' => 'Único',
            'price' => 10,
            'stock' => 1,
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('errors.name.0', 'Já existe um produto com esse nome.');
    }

    #[Test]
    public function it_shows_a_product(): void
    {
        $product = Product::factory()->create();

        $this->authed()->getJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name);
    }

    #[Test]
    public function it_returns_404_when_product_not_found(): void
    {
        $this->authed()->getJson('/api/products/9999')
            ->assertNotFound()
            ->assertJsonStructure(['data', 'message', 'errors']);
    }

    #[Test]
    public function it_updates_a_product(): void
    {
        $product = Product::factory()->create(['name' => 'Antigo']);

        $response = $this->authed()->putJson("/api/products/{$product->id}", [
            'name' => 'Novo',
            'price' => 10,
            'stock' => 1,
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Novo');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Novo']);
    }

    #[Test]
    public function it_deletes_a_product(): void
    {
        $product = Product::factory()->create();

        $this->authed()->deleteJson("/api/products/{$product->id}")
            ->assertOk()
            ->assertJsonStructure(['data', 'message', 'errors']);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
