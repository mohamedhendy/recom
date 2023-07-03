<?php

namespace App\Http\QueryFilters\Article;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class InvoiceArticleStatus extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('status');

        if ($searchValue == 'not_billed') {
            return $builder->whereRaw('article_identity.quantity > article_identity.billed_quantity');
        }


        if ($searchValue == 'not_received') {
            return $builder->whereRaw('article_identity.quantity > article_identity.delivered_quantity');

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
