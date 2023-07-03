<?php

namespace App\Http\QueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

abstract class Filter
{
    public function handle($request, Closure $next): Builder
    {

        if (
            (!request()->has($this->filterName())
                || !request()->filled($this->filterName())
                || request()->input($this->filterName()) == ""
                || request()->input($this->filterName()) == null)
            and !$this->applyAlways()
        ) {
            return $next($request);
        }

        $builder = $next($request);

        return $this->applyFilters($builder);
    }

    /**
     * @return false
     */
    protected function applyAlways(): bool {
        return false;
    }
    /**
     * @param Builder $builder
     * @return mixed
     */
    protected abstract function applyFilters(Builder $builder);

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return Str::snake(class_basename($this));
    }
}
