<?php

namespace App\Http\QueryFilters\Customer;

use Closure;

class  CustomerListRelationships
{
    /**
     * @param         $queryBuilder
     * @param Closure $next
     * @return mixed
     */
    public function handle($queryBuilder, Closure $next)
    {
        return $next($queryBuilder->with('createdBy', 'updatedBy'));
    }

}
