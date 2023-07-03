<?php

namespace App\Jobs\PurchaseOrder;

use App\Jobs\SaleOrder\StoreSaleOrderJob;
use App\Models\Customer;
use App\Models\PurchaseOrderProduct;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreatePurchaseOrderProductRelatedSalesOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var PurchaseOrderProduct
     */
    private $purchaseOrderProduct;
    private $relatedSalesOrderProducts;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrderProduct $purchaseOrderProduct
     * @param                      $relatedSalesOrderProducts
     */
    public function __construct(PurchaseOrderProduct $purchaseOrderProduct, $relatedSalesOrderProducts)
    {
        //
        $this->purchaseOrderProduct = $purchaseOrderProduct;
        $this->relatedSalesOrderProducts = collect($relatedSalesOrderProducts);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->relatedSalesOrderProducts as $relatedSalesOrderProduct) {
            $relatedSalesOrderProduct = collect($relatedSalesOrderProduct);
            $identity = $relatedSalesOrderProduct->get('identity_type') == 'staff' ? Staff::findOrFail($relatedSalesOrderProduct->get('identity_id')) : Customer::findOrFail($relatedSalesOrderProduct->get('identity_id'));
            $products = [
                [
                    'product_id' => $this->purchaseOrderProduct->product_id,
                    'purchase_order_product_id' => $this->purchaseOrderProduct->id,
                    'price' => $relatedSalesOrderProduct->get('price'),
                    'quantity' => $relatedSalesOrderProduct->get('quantity'),
                ]
            ];

            StoreSaleOrderJob::dispatchNow($identity, $products, [
                'issue_date' => $this->purchaseOrderProduct->purchaseOrder->issue_date,
                'due_date' => $this->purchaseOrderProduct->purchaseOrder->due_date,
                'internal_id' => $this->purchaseOrderProduct->purchaseOrder->internal_id,
            ]);
        }
    }
}
