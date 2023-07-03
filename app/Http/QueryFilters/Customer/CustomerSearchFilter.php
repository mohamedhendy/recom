<?php


namespace App\Http\QueryFilters\Customer;

use App\Http\QueryFilters\Common\Filter;
use Illuminate\Database\Eloquent\Builder;


class CustomerSearchFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');

        return $builder->where('customers.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('customers.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('customers.number', 'ILIKE', "%{$searchValue}%")
            ->orWhere('customers.created_at', 'ILIKE', "%{$searchValue}%");

    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}


