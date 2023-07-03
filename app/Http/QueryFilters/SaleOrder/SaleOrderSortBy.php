<?php


namespace App\Http\QueryFilters\SaleOrder;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class SaleOrderSortBy extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $articleIdentityColumns = Schema::getColumnListing('sale_order_products');

        $orderBy = request('orderBy');
        $sortingOrder = 'desc';
        if (in_array(request('orderDirection', $sortingOrder), ['asc', 'desc'])) {
            $sortingOrder = request('orderDirection', $sortingOrder);
        }

        if (in_array($orderBy, $articleIdentityColumns)) {
            return $builder->orderBy($orderBy, $sortingOrder);
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


