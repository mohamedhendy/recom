<?php


namespace App\Http\QueryFilters\WarehouseTransaction;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class WarehouseProductSearchFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->whereHas('warehouseProduct', function (Builder $qq) use ($searchValue) {
            $qq->whereHas('product', function (Builder $qqq) use ($searchValue) {
                $qqq->where('name', 'ILIKE', "%{$searchValue}%");
            });
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
