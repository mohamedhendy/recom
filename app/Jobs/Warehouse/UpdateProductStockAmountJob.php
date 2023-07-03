<?php

namespace App\Jobs\Warehouse;

use App\Models\InventoryProduct;
use App\Models\WarehouseTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductStockAmountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $warehouseTransaction, $warehouseProduct;

    /**
     * Create a new job instance.
     *
     * @param WarehouseTransaction $warehouseTransaction
     */
    public function __construct(WarehouseTransaction $warehouseTransaction)
    {
        $this->warehouseTransaction = $warehouseTransaction;
        $this->warehouseProduct = $warehouseTransaction->warehouseProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $availableQuantity = $this->warehouseProduct->available_qty;

        $stockAmount = $this->getWarehouseProductStockAmount($this->warehouseProduct);
        $unitCost = $availableQuantity > 0 ? $stockAmount / $availableQuantity : $this->warehouseProduct->unit_cost;
        $this->warehouseProduct->update([
            'unit_cost' => $unitCost,
            'stock_amount' => $stockAmount ? $stockAmount : 0,
        ]);
        $this->warehouseTransaction->update([
            'unit_cost' => $unitCost,
            'stock_amount' => $stockAmount ? $stockAmount : 0,
        ]);

        $this->warehouseTransaction->transactionable()->update([
            'unit_cost' => $unitCost,

        ]);
    }

    /**
     * @return int
     */
    private function getWarehouseProductStockAmount()
    {
        if ($this->warehouseTransaction->transactionable) {
            if ($this->warehouseTransaction->transaction_type === 'debit') {
                if ($this->warehouseTransaction->transactionable->price)
                    return $this->warehouseProduct->stock_amount + ($this->warehouseTransaction->transactionable->price * $this->warehouseTransaction->qty);


                if ($this->warehouseTransaction->transactionable instanceof InventoryProduct)
                    return $this->warehouseProduct->stock_amount + ($this->warehouseTransaction->transactionable->unit_cost * $this->warehouseTransaction->qty);

                return $this->warehouseProduct->stock_amount + ($this->warehouseProduct->unit_cost * $this->warehouseTransaction->qty);
            }


            return $this->warehouseProduct->stock_amount - ($this->warehouseProduct->unit_cost * $this->warehouseTransaction->qty);
        }

        return $this->warehouseTransaction->warehouseProduct->stock_amount;
    }
}
