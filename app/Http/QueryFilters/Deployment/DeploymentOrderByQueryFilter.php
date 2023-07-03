<?php

namespace App\Http\QueryFilters\Deployment;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class DeploymentOrderByQueryFilter extends Filter
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

            if ($orderBy === 'a_number') {
                return $builder
                    ->join('assets', 'deployments.asset_id', '=', 'assets.id')
                    ->select("deployments.*", "assets.a_number")
                    ->orderBy("assets.a_number", $sortingOrder);
            }

            if (in_array($orderBy, Schema::getColumnListing('deployments'))) {
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
        return 'orderBy';
    }
}
