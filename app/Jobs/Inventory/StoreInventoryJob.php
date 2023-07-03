<?php

namespace App\Jobs\Inventory;

use App\Jobs\Asset\CreateAssetsJob;
use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\WarehouseProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StoreInventoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;

    /**
     * Create a new job instance.
     *
     * @param $products
     */
    public function __construct($products = [])
    {
        //
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inventoryEntity = Inventory::create([
            'created_by_id' => Auth::id()
        ]);

        foreach ($this->products as $product) {
            $productData = collect($product);
            $productEntity = Product::find($productData->get('product_id'));

            $inventoryProduct = $inventoryEntity->inventoryProducts()->create([
                'created_by_id' => Auth::id(),
                'quantity' => $productData->get('quantity'),
                'unit_cost' => $productData->get('unit_cost'),
                'comment' => $productData->get('comment'),
                'transaction' => $productData->get('transaction'),
                'product_id' => $productData->get('product_id'),
                'warehouse_product_id' => $this->getProductWareHouseInstance($productEntity)->id
            ]);

            CreateProductWareHouseTransactionJob::dispatchNow(null, $productEntity, $inventoryProduct, $productData->get('quantity'), $productData->get('transaction') === 'increase' ? 'debit' : 'credit', auth()->user());
            // REGISTER ASSETS AND CREATE DEPLOYMENTS
            CreateAssetsJob::dispatchNow($productData->get('assets'), $productEntity, null,null, $inventoryProduct->id);
        }
    }


    /**
     * @param $product
     * @return WarehouseProduct|Model
     */
    public function getProductWareHouseInstance($product)
    {
        return $product->warehouseProducts()->where('warehouse_id', 1)->firstOrCreate([
            'warehouse_id' => 1,
            'created_by_id' => auth()->check() ? auth()->id() : 1
        ]);
    }
}
