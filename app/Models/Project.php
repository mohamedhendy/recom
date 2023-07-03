<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int         $id
 * @property string      $name
 * @property string      $description
 * @property string      $status
 *
 * @property int         $customer_id
 * @property Customer    $customer
 *
 * @property SaleOrder[] $saleOrders
 */
class Project extends Base
{
    /**
     * @return Customer|BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function saleOrders(): HasMany
    {
        return $this->hasMany(SaleOrder::class, 'project_id', 'id');
    }
}
