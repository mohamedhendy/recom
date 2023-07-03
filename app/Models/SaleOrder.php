<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int                  $id
 * @property string               $number
 * @property string               $internal_id
 * @property DateTime             $issue_date
 * @property DateTime             $due_date
 * @property string               $status
 * @property DateTime             $creation_year
 * @property string               $identity_type
 * @property int                  $identity_id
 * @property Staff|Customer       $identity
 *
 * @property int                  $project_id
 * @property Project              $project
 * @property PurchaseOrderProduct $purchase_order_product_id
 * @property PurchaseOrderProduct $purchaseOrderProduct
 *
 * @property SaleOrderProduct[]   $saleOrderProducts
 */
class SaleOrder extends Base
{
    protected $appends = ['type'];

    /**
     * @return Project|BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    /**
     * @return Staff|Customer|MorphTo
     */
    public function identity(): MorphTo
    {
        return $this->morphTo('identity');
    }

    /**
     * @return SaleOrderProduct[]|HasMany
     */
    public function saleOrderProducts(): HasMany
    {
        return $this->hasMany(SaleOrderProduct::class, 'sale_order_id', 'id');
    }

    public function getTypeAttribute(): string
    {
        return $this->identity instanceof Customer ? 'customer' : 'staff';
    }

    /**
     * @return PurchaseOrderProduct|BelongsTo
     */
    public function purchaseOrderProduct(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_order_product_id', 'id');
    }

    /**
     * @param null $creationDate
     * @return int
     */
    public static function nextInvoiceNumber($creationDate = null): int
    {

        if ($creationDate) {
            return (new SaleOrder)->whereYear('creation_year', $creationDate->format('Y'))->count() + 1;
        }


        return (new SaleOrder)->whereYear('creation_year', Carbon::now()->format('Y'))->count() + 1;
    }
}
