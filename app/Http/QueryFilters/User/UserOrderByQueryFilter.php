<?php

namespace App\Http\QueryFilters\User;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class UserOrderByQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        if ($orderBy = request('orderBy', 'id')) {

            $sortingOrder = 'desc';

            if (in_array(request('orderDirection', $sortingOrder), ['asc', 'desc'])) {
                $sortingOrder = request('orderDirection', $sortingOrder);
            }

            if (in_array($orderBy, Schema::getColumnListing('users'))) {
                return $builder->orderBy($orderBy, $sortingOrder);
            }
        }

        return $builder;
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'orderBy';
    }
}
