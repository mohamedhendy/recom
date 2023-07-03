<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property double $price
 * @property double $quantity
 * @property string $description
 * @property double $unit_cost
 * @property string $currency
 * @property double $billed_quantity
 *
 * @property int $product_id
 * @property Product $product
 * @property int $sale_order_id
 * @property SaleOrder $saleOrder
 * @property int $purchase_order_product_id
 * @property PurchaseOrderProduct $purchaseOrderProduct
 *
 * @property Asset[] $assets
 * @property mixed delivered_quantity
 * @method static notStock()
 */
class SaleOrderProduct extends Base
{
    protected $appends = ['identity_id', 'identity_type','is_to_stock', 'identity_name',
        'available_quantity_for_billing','not_received_quantity'];


    /**
     * @return Product|BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * @return SaleOrder|BelongsTo
     */
    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct|BelongsTo
     */
    public function purchaseOrderProduct(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_order_product_id', 'id');
    }

    /**
     * @return Asset[]|HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'sale_order_product_id', 'id')->orderBy('id');
    }

    /**
     * @return MorphMany
     */
    public function inventoryTransactions(): MorphMany
    {
        return $this->morphMany(WarehouseTransaction::class, 'transactionable');
    }

    public function getIdentityIdAttribute(): int
    {
        return $this->saleOrder->identity_id;
    }

    public function getIdentityNameAttribute(): string
    {
        return $this->saleOrder->identity->name;
    }

    public function getIdentityTypeAttribute(): string
    {
        return $this->saleOrder->identity instanceof Customer ? 'customer' : 'staff';
    }


    public function getIsToStockAttribute(): bool
    {
        return $this->saleOrder->identity instanceof Customer ? $this->saleOrder->identity->type === 'stock' : false;
    }

    public function getAvailableQuantityForBillingAttribute(): float
    {
        return $this->quantity - $this->billed_quantity;

    }

    public function getNotReceivedQuantityAttribute(): float
    {
        return $this->quantity - $this->delivered_quantity;

    }


    public function scopeNotStock($query)
    {
        return $query->whereHas('saleOrder.identity', function ($q) {
            $q->where('type', '!=', 'stock');
        });
    }

    public function scopeNotBilled($query)
    {
        return $query->whereRaw('sale_order_products.quantity > sale_order_products.billed_quantity');
    }

    public function scopeNotReceived($query)
    {
        return $query->whereHas('purchaseOrderProduct', function ($query) {
            $query->whereRaw('quantity > delivered_quantity');
        });
    }
}
