<?php


namespace App\Http\QueryFilters\Common;

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

        if ($this->isExists()) {
            $sortingOrder = 'desc';
            if (in_array(request('orderDirection', $sortingOrder), ['asc', 'desc'])) {
                $sortingOrder = request('orderDirection', $sortingOrder);
            }
            if ($this->isTableColumn($builder)) {
                return $builder->orderBy($this->filterValue(), $sortingOrder);
            }
        }


        return $builder->orderBy('id', 'desc');

    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'orderBy';
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function getTableName(Builder $builder): string
    {
        return $builder->getModel()->getTable();
    }

    /**
     * @param Builder $builder
     * @return bool
     */
    public function isTableColumn(Builder $builder): bool
    {
        $table = $this->getTableName($builder);
        return in_array(request($this->filterName()), Schema::getColumnListing($table));
    }

    /**
     * @return bool
     */
    protected function isRequired(): bool
    {
        return true;
    }
}
