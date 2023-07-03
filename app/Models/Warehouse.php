<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                    $id
 * @property string                 $name
 * @property string                 $description
 *
 * @property WarehouseProduct[]     $warehouseProducts
 * @property WarehouseTransaction[] $warehouseTransactions
 */
class Warehouse extends Base
{

    /**
     * @return WarehouseProduct[]|HasMany
     */
    public function warehouseProducts(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class, 'warehouse_id', 'id');
    }

    /**
     * @return WarehouseTransaction[]|HasMany
     */
    public function warehouseTransactions(): HasMany
    {
        return $this->hasMany(WarehouseTransaction::class, 'warehouse_id', 'id');
    }
}
