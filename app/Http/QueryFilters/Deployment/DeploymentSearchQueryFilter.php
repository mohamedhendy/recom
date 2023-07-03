<?php

namespace App\Http\QueryFilters\Deployment;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DeploymentSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder
            ->where('deployments.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.name', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.o_number', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.type', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.address', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.building', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.room', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.exact_position', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.info', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deployments.contact', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('asset', function (Builder $qq) use ($searchValue) {
                $qq->where('assets.a_number', 'ILIKE', "%{$searchValue}%");
            });
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
