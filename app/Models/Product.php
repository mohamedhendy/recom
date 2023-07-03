<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int                        $id
 * @property string                     $name
 * @property string                     $ean_number
 * @property float                      $default_sale_price
 * @property float                      $default_purchase_price
 * @property string                     $manufacturer
 * @property string                     $model
 * @property                            $default_info
 *
 * @property int                        $category_id
 * @property Category                   $category
 *
 * @property SaleOrderProduct[]         $saleOrderProducts
 * @property PurchaseOrderProduct[]     $purchaseOrderProducts
 * @property WarehouseProduct[]|HasMany $warehouseProducts
 * @property WarehouseTransaction[]     $warehouseTransactions
 * @property ProductSlot[]              $productSlots
 * @property mixed                      $available_qty
 * @property mixed unit_cost_average
 */
class Product extends Base
{

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->ean_number . ' ' . $this->name;
    }

    /**
     * @return Category|BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return SaleOrderProduct[]|HasMany
     */
    public function saleOrderProducts(): HasMany
    {
        return $this->hasMany(SaleOrderProduct::class, 'product_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct[]|HasMany
     */
    public function purchaseOrderProducts(): HasMany
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'product_id', 'id');
    }

    /**
     * @return Asset[]|HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'product_id', 'id');
    }

    /**
     * @return WarehouseProduct[]|HasMany
     */
    public function warehouseProducts(): HasMany
    {
        return $this->hasMany(WarehouseProduct::class, 'product_id', 'id');
    }

    /**
     * @return WarehouseTransaction[]|HasMany
     */
    public function warehouseTransactions(): HasMany
    {
        return $this->hasMany(WarehouseTransaction::class, 'product_id', 'id');
    }

    /**
     * @return ProductSlot[]|HasMany
     */
    public function productSlots(): HasMany
    {
        return $this->hasMany(ProductSlot::class, 'product_id', 'id');
    }

    /**
     * @return int
     */
    public function getAvailableQtyAttribute(): int
    {
        return $this->warehouseProducts->sum('available_qty');
    }

    public function getUnitCostAverageAttribute()
    {
        $unitCost = 0;
        $warehouse = $this->warehouseProducts->first();
        if ($warehouse) $unitCost = $warehouse->unit_cost;
        return $unitCost;
    }
}
