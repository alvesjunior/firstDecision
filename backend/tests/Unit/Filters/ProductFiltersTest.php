<?php

namespace Tests\Unit\Filters;

use App\Http\Filters\ProductFilters;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductFiltersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Product::factory()->create(['name' => 'Cadeira Gamer', 'price' => 1500, 'stock' => 10]);
        Product::factory()->create(['name' => 'Mesa Office', 'price' => 800, 'stock' => 0]);
        Product::factory()->create(['name' => 'Teclado Mecânico', 'price' => 350, 'stock' => 50]);
    }

    private function filtersFor(array $params): ProductFilters
    {
        return new ProductFilters(new Request($params));
    }

    #[Test]
    public function it_filters_by_search_term(): void
    {
        $query = Product::query();
        $this->filtersFor(['search' => 'cadeira'])->apply($query);

        $this->assertSame(1, $query->count());
        $this->assertSame('Cadeira Gamer', $query->first()->name);
    }

    #[Test]
    public function it_filters_by_price_range(): void
    {
        $query = Product::query();
        $this->filtersFor(['min_price' => 500, 'max_price' => 1000])->apply($query);

        $this->assertSame(1, $query->count());
        $this->assertSame('Mesa Office', $query->first()->name);
    }

    #[Test]
    public function it_filters_by_stock_range(): void
    {
        $query = Product::query();
        $this->filtersFor(['min_stock' => 1])->apply($query);

        $this->assertSame(2, $query->count());
    }

    #[Test]
    public function it_only_sorts_by_allowed_fields(): void
    {
        $query = Product::query();
        $this->filtersFor(['sort' => 'unknown_column', 'direction' => 'asc'])->apply($query);

        $this->assertEmpty($query->getQuery()->orders ?? []);
    }

    #[Test]
    public function it_sorts_by_price_desc(): void
    {
        $query = Product::query();
        $this->filtersFor(['sort' => 'price', 'direction' => 'desc'])->apply($query);

        $names = $query->get()->pluck('name')->all();
        $this->assertSame(['Cadeira Gamer', 'Mesa Office', 'Teclado Mecânico'], $names);
    }
}
