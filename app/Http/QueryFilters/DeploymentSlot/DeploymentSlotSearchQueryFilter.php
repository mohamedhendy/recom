<?php

namespace App\Http\QueryFilters\DeploymentSlot;

use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class DeploymentSlotSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder->where('deploymentSlots.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deploymentSlots.info', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deploymentSlots.deployment_id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('deploymentSlots.product_slot_id', 'ILIKE', "%{$searchValue}%");
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
