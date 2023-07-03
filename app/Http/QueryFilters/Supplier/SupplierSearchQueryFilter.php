<?php


namespace App\Http\QueryFilters\Supplier;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SupplierSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('suppliers.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('suppliers.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('suppliers.number', 'ILIKE', "%{$searchValue}%");

    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}


