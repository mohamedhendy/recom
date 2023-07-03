<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int                                                    $id
 * @property string                                                 $transaction_type
 * @property int                                                    $transaction_id
 * @property SaleOrderProduct|PurchaseOrderProduct|InventoryProduct $transactionable
 * @property int                                                    $qty
 * @property string                                                 $description
 * @property int                                                    $warehouse_product_id
 * @property WarehouseProduct                                       $warehouseProduct
 * @property int                                                    $unit_cost
 * @property int                                                    $stock_amount
 * @property int                                                    $available_qty
 */
class WarehouseTransaction extends Base
{
    /**
     * @return WarehouseProduct|BelongsTo
     */
    public function warehouseProduct(): BelongsTo
    {
        return $this->belongsTo(WarehouseProduct::class, 'warehouse_product_id', 'id');
    }

    /**
     * @return SaleOrderProduct|PurchaseOrderProduct|InventoryProduct|MorphTo
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo('transactionable');
    }
}
