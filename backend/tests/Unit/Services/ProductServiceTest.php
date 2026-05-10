<?php

namespace Tests\Unit\Services;

use App\Http\Filters\ProductFilters;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_clamps_per_page_to_safe_bounds(): void
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('paginate')
            ->once()
            ->withArgs(fn (ProductFilters $f, int $perPage) => $perPage === 100)
            ->andReturn(new LengthAwarePaginator(new Collection(), 0, 100));

        $service = new ProductService($repo);
        $service->list(new ProductFilters(request()), 5000);

        $this->assertTrue(true);
    }

    #[Test]
    public function it_clamps_per_page_minimum_to_one(): void
    {
        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('paginate')
            ->once()
            ->withArgs(fn (ProductFilters $f, int $perPage) => $perPage === 1)
            ->andReturn(new LengthAwarePaginator(new Collection(), 0, 1));

        (new ProductService($repo))->list(new ProductFilters(request()), 0);

        $this->assertTrue(true);
    }

    #[Test]
    public function it_creates_a_product_via_repository(): void
    {
        $attrs = ['name' => 'X', 'description' => null, 'price' => 9.9, 'stock' => 1];
        $product = (new Product)->forceFill(['id' => 1] + $attrs);

        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('create')->once()->with($attrs)->andReturn($product);

        $result = (new ProductService($repo))->create($attrs);

        $this->assertSame($product, $result);
    }

    #[Test]
    public function it_finds_then_updates_product(): void
    {
        $product = (new Product)->forceFill(['id' => 7, 'name' => 'Old']);
        $attrs = ['name' => 'New'];

        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('findOrFail')->once()->with(7)->andReturn($product);
        $repo->shouldReceive('update')->once()->with($product, $attrs)->andReturn($product);

        $result = (new ProductService($repo))->update(7, $attrs);

        $this->assertSame($product, $result);
    }

    #[Test]
    public function it_finds_then_deletes_product(): void
    {
        $product = (new Product)->forceFill(['id' => 4]);

        $repo = Mockery::mock(ProductRepositoryInterface::class);
        $repo->shouldReceive('findOrFail')->once()->with(4)->andReturn($product);
        $repo->shouldReceive('delete')->once()->with($product);

        (new ProductService($repo))->delete(4);

        $this->assertTrue(true);
    }
}
