<?php

namespace App\Http\QueryFilters\PurchaseOrder;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderDeliveryStatus extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('status');


        if ($searchValue == 'not_received') {
            return $builder->whereRaw('purchase_order_products.quantity > purchase_order_products.delivered_quantity');

        }
        return $builder;

    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'status';
    }
}
