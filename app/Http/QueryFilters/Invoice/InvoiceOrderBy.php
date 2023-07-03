<?php

namespace App\Http\QueryFilters\Invoice;

use App\Http\QueryFilters\Filter;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class InvoiceOrderBy extends Filter
{

    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {

        if ($orderBy = request('orderBy', 'id')) {
            $table = with(new Invoice())->getTable();
            $columns = Schema::getColumnListing($table);

            if (in_array($orderBy, $columns)) {
                $sortingOrder = 'desc';

                if (in_array(request('sort_order', $sortingOrder), ['asc', 'desc'])) {
                    $sortingOrder = request('sort_order', $sortingOrder);
                }

                return $builder->orderBy($orderBy, $sortingOrder);
            }
        }


        return $builder;
        // TODO: Implement applyFilters() method.
    }

    protected function filterName(): string
    {
        return 'orderBy';
    }
}
