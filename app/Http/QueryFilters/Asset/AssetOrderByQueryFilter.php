<?php

namespace App\Http\QueryFilters\Asset;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class AssetOrderByQueryFilter extends Filter
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

            if ($orderBy === 'product.name') {
                return $builder
                    ->join('products', 'assets.product_id', '=', 'products.id')
                    ->select("assets.*", "products.name")
                    ->orderBy("products.name", $sortingOrder);
            }

            if (in_array($orderBy, Schema::getColumnListing('assets'))) {
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
