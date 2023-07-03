<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                  $id
 * @property string               $a_number
 * @property string               $serial_number
 * @property string               $description
 * @property int                  $product_id
 * @property Product              $product
 * @property int                  $sale_order_product_id
 * @property SaleOrderProduct     $saleOrderProduct
 * @property int                  $purchase_order_product_id
 * @property PurchaseOrderProduct $purchaseOrderProduct
 * @property int                  $inventory_product_id
 * @property InventoryProduct     $inventoryProduct
 * @method  stockAssets
 * @property Deployment[]         $deployments
 */
class Asset extends Base
{
    /**
     * @return Product|BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return SaleOrderProduct|BelongsTo
     */
    public function saleOrderProduct(): BelongsTo
    {
        return $this->belongsTo(SaleOrderProduct::class, 'sale_order_product_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct|BelongsTo
     */
    public function purchaseOrderProduct(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_order_product_id', 'id');
    }

    /**
     * @return InventoryProduct|BelongsTo
     */
    public function inventoryProduct(): BelongsTo
    {
        return $this->belongsTo(InventoryProduct::class, 'inventory_product_id', 'id');
    }

    /**
     * @return Deployment[]|HasMany
     */
    public function deployments(): HasMany
    {
        return $this->hasMany(Deployment::class, 'asset_id', 'id');
    }

    public function scopeNotDeployed($query)
    {
        return $query->where('is_deployed',false);
    }
    public function scopeStockAssets($query)
    {
        return $query->whereHas('saleOrderProduct.saleOrder.identity',function($subQuery) {
            return $subQuery->where('type','stock');
        })->orWhere([
            ['purchase_order_product_id',null],
            ['sale_order_product_id',null],
            ['inventory_product_id','!=',null],

        ]);
    }
}
