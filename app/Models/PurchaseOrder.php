<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $number
 * @property string $internal_id
 * @property DateTime $issue_date
 * @property DateTime $due_date
 * @property string $status
 * @property DateTime $creation_year
 *
 * @property int $supplier_id
 * @property Supplier $supplier
 *
 * @property PurchaseOrderProduct[] $purchaseOrderProducts
 */
class PurchaseOrder extends Base
{


    /**
     * @param null $creationDate
     * @return int
     */
    public static function nextPurchaseOrderId($creationDate = null): int
    {
        if ($creationDate == null) {
            $creationDate = Carbon::now();
        }
        $year = $creationDate->format('Y');

        $purchasesCount = DB::table('purchase_orders')->whereYear('creation_year', $year)->count();
        return ((int)$purchasesCount + 1);
    }

    /**
     * @param $date
     * @return string
     */
    public function getCreationYearAttribute($date)
    {
        return Carbon::parse($date)->format("Y");
    }

    /**
     * @return Supplier|BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    /**
     * @return PurchaseOrderProduct[]|HasMany
     */
    public function purchaseOrderProducts(): HasMany
    {
        return $this->hasMany(PurchaseOrderProduct::class, 'purchase_order_id', 'id');
    }


}
