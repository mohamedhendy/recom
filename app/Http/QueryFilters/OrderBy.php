<?php


namespace App\Http\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class OrderBy extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {


        if ($orderBy = request('orderBy', 'id')) {

            $sortingOrder = 'desc';

            if (in_array(request('orderDirection', $sortingOrder), ['asc', 'desc'])) {
                $sortingOrder = request('orderDirection', $sortingOrder);
            }


            if ($orderBy === 'supplier_id') {
                return $builder->join('suppliers', 'article_identity.supplier_id', '=', 'suppliers.id')
                    ->select("article_identity.*", "suppliers.name")
                    ->orderBy("suppliers.name", $sortingOrder);
            }

            if ($orderBy == 'user_id') {

                return $builder->join('customers', 'article_identity.identity_id', '=', 'customers.id')
                    ->select("article_identity.*", "customers.name")
                    ->orderBy("customers.name", $sortingOrder);
            }


            $articleIdentityColumns = Schema::getColumnListing('articles');

            if (in_array($orderBy, $articleIdentityColumns) && $orderBy != 'id') {

                return $builder->join('articles', 'article_identity.article_id', '=', 'articles.id')
                    ->select('article_identity.*', 'articles.name')
                    ->orderBy("articles.$orderBy", $sortingOrder);
            }


            if (in_array($orderBy, $articleIdentityColumns)) {

                return $builder->orderBy($orderBy, $sortingOrder);
            }
        }


        return $builder;
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'orderBy'; // TODO: Change the autogenerated stub
    }
}
