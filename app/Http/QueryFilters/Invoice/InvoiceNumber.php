<?php

namespace App\Http\QueryFilters\Invoice;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InvoiceNumber extends Filter
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where('invoice_number', request($this->filterName()));
    }
}
