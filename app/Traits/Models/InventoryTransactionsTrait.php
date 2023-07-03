<?php

namespace App\Traits\Models;

use App\Models\WarehouseTransaction;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait InventoryTransactionsTrait
{

    /**
     * @return MorphMany
     */
    public function inventoryTransactions(): MorphMany
    {
        return $this->morphMany(WarehouseTransaction::class, 'transactionable');
    }
}
