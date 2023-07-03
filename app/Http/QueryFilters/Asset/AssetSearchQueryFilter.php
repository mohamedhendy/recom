<?php


namespace App\Http\QueryFilters\Asset;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class AssetSearchQueryFilter extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');
        return $builder
            ->where('assets.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('assets.a_number', 'ILIKE', "%{$searchValue}%")
            ->orWhere('assets.serial_number', 'ILIKE', "%{$searchValue}%")
            ->orWhere('assets.description', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('product', function (Builder $qq) use ($searchValue) {
                $qq->where('products.name', 'ILIKE', "%{$searchValue}%");
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


