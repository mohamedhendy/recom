<?php

namespace App\Http\QueryFilters\ProductSlot;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ProductSlotSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('product_slots.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('product_slots.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('product_slots.number', 'ILIKE', "%{$searchValue}%");
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
