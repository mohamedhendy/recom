<?php

namespace App\Http\QueryFilters\Product;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class ProductOrderByQueryFilter extends Filter
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


            // To Do => allow order by sum of all warehouses available qty
            if ($orderBy == 'available_qty') {
                return $builder->join('warehouse_products', 'products.id', '=', 'warehouse_products.product_id')
                    ->select("products.*", "warehouse_products.available_qty")
                    ->orderBy("warehouse_products.available_qty", $sortingOrder);
            }


            if (in_array($orderBy, Schema::getColumnListing('products'))) {
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
