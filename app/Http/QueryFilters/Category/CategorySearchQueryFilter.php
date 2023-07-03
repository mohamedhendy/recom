<?php

namespace App\Http\QueryFilters\Category;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CategorySearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('categories.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('categories.name', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('parentCategory', function ($cat) use ($searchValue) {
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
