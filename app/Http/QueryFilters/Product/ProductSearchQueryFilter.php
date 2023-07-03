<?php

namespace App\Http\QueryFilters\Product;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('products.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('products.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('products.ean_number', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('category', function ($cat) use ($searchValue) {
                $cat->where('name', 'ILIKE', "%{$searchValue}%");
            });
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
