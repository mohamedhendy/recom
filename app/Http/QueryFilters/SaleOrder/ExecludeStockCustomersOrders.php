<?php

namespace App\Http\QueryFilters\SaleOrder;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ExecludeStockCustomersOrders extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {

        if(request()->has('active_page') && request()->input('active_page') === 'sales_page')
        {
            return $builder->whereHas('saleOrder.identity',function($query) {
                $query->where('type','!=','stock');
            });
        }

        return $builder;

    }

    /**
     * @return bool
     */
    protected function applyAlways(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'stock_customer';
    }
}
