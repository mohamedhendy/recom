<?php


namespace App\Http\QueryFilters\Article;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class ApplyInvoiceTypeFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('invoice_type');
//
//        if (in_array($searchValue, ['purchase','sale','return-sale','return-purchase', 'beginning-inventory'])) {
//            return $builder->where('article_identity.type', $searchValue);
//        }
        return $builder;


    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'invoice_type';
    }
}


