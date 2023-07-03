<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddPurchaseOrderProductDeliveredQuantityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var PurchaseOrderProduct
     */
    private $purchaseOrderProduct;
    private $deliveredQuantity;
    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrderProduct $purchaseOrderProduct
     * @param SaleOrderProduct $saleOrderProduct
     * @param $deliveredQuantity
     */
    public function __construct(PurchaseOrderProduct  $purchaseOrderProduct,SaleOrderProduct $saleOrderProduct,$deliveredQuantity)
    {
        //
        $this->purchaseOrderProduct = $purchaseOrderProduct;
        $this->deliveredQuantity = $deliveredQuantity;
        $this->saleOrderProduct = $saleOrderProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->purchaseOrderProduct->update([
            'delivered_quantity' => DB::raw("delivered_quantity + {$this->deliveredQuantity}")
        ]);

        $this->saleOrderProduct->update([
            'delivered_quantity' => DB::raw("delivered_quantity + {$this->deliveredQuantity}")
        ]);
    }
}
