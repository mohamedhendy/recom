<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int              $id
 * @property double           $price
 * @property double           $quantity
 * @property string           $description
 * @property double           $unit_cost
 * @property string           $currency
 * @property double           $billed_quantity
 * @property double           $delivered_quantity
 * @property bool             $documents_upload
 *
 * @property int              $product_id
 * @property Product          $product
 * @property int              $purchase_order_id
 * @property PurchaseOrder    $purchaseOrder
 *
 * @property Asset[]          $assets
 * @property SaleOrder[]      $saleOrders
 * @property SaleOrderProduct $relatedSaleOrdersProducts
 */
class PurchaseOrderProduct extends Base
{
    protected $appends = ['available_quantity_for_billing', 'not_received_quantity'];

    /**
     * @return Product|BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return PurchaseOrder|BelongsTo
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    /**
     * @return Asset[]|HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'purchase_order_product_id', 'id')->orderBy('id');
    }

    /**
     * @return SaleOrder[]|HasMany
     */
    public function saleOrders(): HasMany
    {
        return $this->hasMany(SaleOrder::class, 'purchase_order_product_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function inventoryTransactions(): MorphMany
    {
        return $this->morphMany(WarehouseTransaction::class, 'transactionable');
    }

    /**
     * @return SaleOrderProduct|hasMany
     */
    public function relatedSaleOrdersProducts(): hasMany
    {
        return $this->hasMany(SaleOrderProduct::class, 'purchase_order_product_id', 'id');
    }

    public function getAvailableQuantityForBillingAttribute()
    {
        return $this->delivered_quantity - $this->relatedSaleOrdersProducts->sum('billed_quantity');
    }

    public function getNotReceivedQuantityAttribute()
    {
        return $this->quantity - $this->delivered_quantity;
    }
}
