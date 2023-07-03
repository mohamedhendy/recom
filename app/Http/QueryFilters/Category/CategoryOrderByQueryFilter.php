<?php

namespace App\Http\QueryFilters\Category;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class CategoryOrderByQueryFilter extends Filter
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


            if ($orderBy == 'parent_name') {
                return $builder->join('categories as pCategories', 'categories.parent_category_id', '=', 'pCategories.id')
                    ->select("categories.*", "pCategories.name")
                    ->orderBy("pCategories.name", $sortingOrder);
            }
            if (in_array($orderBy, Schema::getColumnListing('categories'))) {
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
