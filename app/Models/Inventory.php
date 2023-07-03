<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property InventoryProduct[]|HasMany $inventoryProducts
 */
class Inventory extends Base
{

    /**
     * @return int
     */
    public function getInventoryProductsCountAttribute(): int
    {
        return $this->inventoryProducts->count();
    }

    /**
     * @return InventoryProduct[]|HasMany
     */
    public function inventoryProducts(): HasMany
    {
        return $this->hasMany(InventoryProduct::class, 'inventory_id');
    }
}
