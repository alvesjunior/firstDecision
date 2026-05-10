<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreProductRequestTest extends TestCase
{
    use RefreshDatabase;

    private function validate(array $data): \Illuminate\Validation\Validator
    {
        $rules = (new StoreProductRequest)->rules();

        return Validator::make($data, $rules);
    }

    #[Test]
    public function it_passes_for_valid_data(): void
    {
        $v = $this->validate([
            'name' => 'Notebook',
            'description' => 'Bom estado',
            'price' => 3500.50,
            'stock' => 12,
        ]);

        $this->assertTrue($v->passes(), implode(' | ', $v->errors()->all()));
    }

    #[Test]
    public function it_requires_name_price_and_stock(): void
    {
        $v = $this->validate([]);

        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('name', $v->errors()->toArray());
        $this->assertArrayHasKey('price', $v->errors()->toArray());
        $this->assertArrayHasKey('stock', $v->errors()->toArray());
    }

    #[Test]
    public function it_rejects_negative_price_and_negative_stock(): void
    {
        $v = $this->validate(['name' => 'X', 'price' => -1, 'stock' => -2]);

        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('price', $v->errors()->toArray());
        $this->assertArrayHasKey('stock', $v->errors()->toArray());
    }

    #[Test]
    public function it_enforces_unique_product_name(): void
    {
        Product::factory()->create(['name' => 'Repetido']);

        $v = $this->validate(['name' => 'Repetido', 'price' => 10, 'stock' => 1]);

        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('name', $v->errors()->toArray());
    }
}
