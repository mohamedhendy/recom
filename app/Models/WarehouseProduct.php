<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int       $id
 * @property int       $available_qty
 * @property int       $product_id
 * @property Product   $product
 * @property int       $warehouse_id
 * @property Warehouse $warehouse
 * @property int       $unit_cost
 * @property int       $stock_amount
 */
class WarehouseProduct extends Base
{
    /**
     * @return Product|BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return Warehouse|BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
