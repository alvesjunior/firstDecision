<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilters
{
    public function __construct(private readonly Request $request)
    {
    }

    public function apply(Builder $query): Builder
    {
        return $query
            ->when($this->request->filled('search'), $this->search(...))
            ->when($this->request->filled('min_price'), $this->minPrice(...))
            ->when($this->request->filled('max_price'), $this->maxPrice(...))
            ->when($this->request->filled('min_stock'), $this->minStock(...))
            ->when($this->request->filled('max_stock'), $this->maxStock(...))
            ->when($this->request->filled('sort'), $this->sort(...));
    }

    private function search(Builder $query): void
    {
        $term = trim((string) $this->request->input('search'));

        $query->where(function (Builder $q) use ($term): void {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    private function minPrice(Builder $query): void
    {
        $query->where('price', '>=', (float) $this->request->input('min_price'));
    }

    private function maxPrice(Builder $query): void
    {
        $query->where('price', '<=', (float) $this->request->input('max_price'));
    }

    private function minStock(Builder $query): void
    {
        $query->where('stock', '>=', (int) $this->request->input('min_stock'));
    }

    private function maxStock(Builder $query): void
    {
        $query->where('stock', '<=', (int) $this->request->input('max_stock'));
    }

    private function sort(Builder $query): void
    {
        $allowed = ['name', 'price', 'stock', 'created_at'];
        $field = (string) $this->request->input('sort');
        $direction = strtolower((string) $this->request->input('direction', 'asc')) === 'desc' ? 'desc' : 'asc';

        if (in_array($field, $allowed, true)) {
            $query->orderBy($field, $direction);
        }
    }
}
