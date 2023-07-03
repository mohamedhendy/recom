<?php

namespace App\Http\QueryFilters\User;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UserSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('users.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('users.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('users.email', 'ILIKE', "%{$searchValue}%");
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
