<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class UpdateProductAvailableQuantityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $warehouseTransaction, $productWarehouse;

    /**
     * Create a new job instance.
     *
     * @param $warehouseTransaction
     * @param $productWarehouse
     */
    public function __construct($warehouseTransaction, $productWarehouse)
    {
        $this->warehouseTransaction = $warehouseTransaction;
        $this->productWarehouse = $productWarehouse;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ValidationException
     */
    public function handle()
    {
        if ($this->warehouseTransaction->transaction_type == 'credit')
            $newAvailableQty = (float)$this->productWarehouse->available_qty - (float)$this->warehouseTransaction->qty;
        else
            $newAvailableQty = (float)$this->productWarehouse->available_qty + (float)$this->warehouseTransaction->qty;

        if ((float)$newAvailableQty < 0) {
            $errorKey = Session::get('invalid_available_quantity_error_key', null);
            if (!$errorKey) {
                $errorKey = 'available_qty';
            }
            throw ValidationException::withMessages([
                $errorKey => "Inventory does't have enough available quantity for this product purchase/increase inventory first"
            ]);
        }

        $this->productWarehouse->update([
            'available_qty' => $newAvailableQty
        ]);

        $this->warehouseTransaction->update([
            'available_qty' => $newAvailableQty
        ]);
    }
}
