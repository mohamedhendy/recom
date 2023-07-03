<?php

namespace App\Jobs\Warehouse;

use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateProductWareHouseTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $warehouse, $product, $transationable, $quantity, $transactionType, $createdBy;

    /**
     * Create a new job instance.
     *
     * @param Warehouse|null             $warehouse
     * @param Product                    $product
     * @param                            $transationable
     * @param                            $quantity
     * @param string                     $transactionType
     * @param User                       $createdBy
     * @throws Exception
     */
    public function __construct($warehouse = null, Product $product, $transationable, $quantity, string $transactionType, User $createdBy)
    {
        if (!in_array($transactionType, ['credit', 'debit'])) {
            throw new Exception('wrong transaction type');
        }

        $this->warehouse = $warehouse == null ? Warehouse::first() : $warehouse;
        $this->createdBy = $createdBy;
        $this->product = $product;
        $this->transationable = $transationable;
        $this->quantity = $quantity;
        $this->transactionType = $transactionType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->quantity > 0) {
            $productWarehouse = $this->getProductWareHouseInstance();
            $warehouseTransaction = $this->transationable->inventoryTransactions()->create([
                'warehouse_product_id' => $productWarehouse->id,
                'transaction_type' => $this->transactionType,
                'qty' => $this->quantity,
                'created_by_id' => $this->createdBy->id
            ]);
            UpdateProductAvailableQuantityJob::dispatchNow($warehouseTransaction, $productWarehouse);
            UpdateProductStockAmountJob::dispatchNow($warehouseTransaction);
        }

    }

    /**
     * @return WarehouseProduct|Model
     */
    public function getProductWareHouseInstance()
    {

        $warehouse = $this->product->warehouseProducts()->where('warehouse_id', $this->warehouse->id)->first();
        if (!$warehouse) {
            $warehouse = WarehouseProduct::create(
                [
                    'warehouse_id' => $this->warehouse->id,
                    'product_id' => $this->product->id,
                    'created_by_id' => auth()->check() ? auth()->id() : 1
                ]
            );
        }

        return $warehouse;
    }

}
