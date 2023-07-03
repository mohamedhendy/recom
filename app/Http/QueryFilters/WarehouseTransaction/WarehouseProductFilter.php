<?php


namespace App\Http\QueryFilters\WarehouseTransaction;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class WarehouseProductFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {

        return $builder->where('warehouse_product_id', (int)request('warehouse_product_id'));


    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'warehouse_product_id';
    }
}


