<?php

namespace App\Http\QueryFilters\SaleOrder;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SaleOrderBillingStatus extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('status');


        if ($searchValue == 'not_billed') {
            return $builder->notStock()->notBilled();

        }

        if ($searchValue == 'not_received') {
            return $builder->notReceived();

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
