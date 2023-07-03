<?php

namespace App\Http\QueryFilters\Common;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter
{
    /**
     * @param         $queryBuilder
     * @param Closure $next
     * @return mixed
     */
    public function handle($queryBuilder, Closure $next)
    {

        if (!request()->has($this->filterName()) && !$this->isRequired()) {
            return $next($queryBuilder);
        }
        $builder = $next($queryBuilder);
        return $this->applyFilters($builder);
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

    /**
     * @return array|Application|Request|string
     */
    protected function filterValue()
    {
        return request($this->filterName());
    }

    /**
     * @return bool
     */
    protected function isExists(): bool
    {
        return request()->has($this->filterName()) && request()->filled($this->filterName());
    }

    /**
     * @return bool
     */
    protected function isRequired(): bool
    {
        return false;
    }
}
