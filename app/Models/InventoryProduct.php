<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class InventoryProduct extends Base
{
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }


    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'inventory_product_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return MorphMany
     */
    public function inventoryTransactions(): MorphMany
    {
        return $this->morphMany(WarehouseTransaction::class, 'transactionable');
    }
}
