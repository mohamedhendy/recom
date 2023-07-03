<?php

namespace App\Http\QueryFilters\Invoice;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InvoiceSearch extends Filter
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->join('suppliers', 'suppliers.id', '=', 'invoices.supplier_id')
            ->select('invoices.*', 'suppliers.supplier_name')
            ->orWhere('invoices.invoice_number', 'LIKE', "%{$searchValue}%")
            ->orWhere('invoices.internal_invoice_number', 'LIKE', "%{$searchValue}%")
            ->orWhereRaw("lower(suppliers.supplier_name) LIKE '%" . strtolower($searchValue) . "%'");
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
