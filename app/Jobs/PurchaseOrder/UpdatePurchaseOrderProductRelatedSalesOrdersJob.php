<?php

namespace App\Jobs\PurchaseOrder;

use App\Jobs\SaleOrder\StoreSaleOrderJob;
use App\Jobs\SaleOrder\UpdateSaleOrderProductJob;
use App\Models\Customer;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrder;
use App\Models\SaleOrderProduct;
use App\Models\Staff;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePurchaseOrderProductRelatedSalesOrdersJob implements ShouldQueue
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
     * @throws Exception
     */
    public function handle()
    {
        $notDeletedSalesOrders = [];
        foreach ($this->relatedSalesOrderProducts as $relatedSalesOrderProduct) {

            $relatedSalesOrderProduct = collect($relatedSalesOrderProduct);
            $identity = $relatedSalesOrderProduct->get('identity_type') == 'staff' ? Staff::findOrFail($relatedSalesOrderProduct->get('identity_id')) : Customer::findOrFail($relatedSalesOrderProduct->get('identity_id'));
            $productData = [
                'product_id' => $this->purchaseOrderProduct->product_id,
                'purchase_order_product_id' => $this->purchaseOrderProduct->id,
                'price' => $relatedSalesOrderProduct->get('price'),
                'quantity' => $relatedSalesOrderProduct->get('quantity'),
            ];

            if ($relatedSalesOrderProduct->has('id')) {
                $saleProductOrder = SaleOrderProduct::find($relatedSalesOrderProduct->get('id'));
                if ($saleProductOrder) {
                    $notDeletedSalesOrders[] = $saleProductOrder->sale_order_id;
                    UpdateSaleOrderProductJob::dispatchNow($saleProductOrder, $productData, $identity, get_class($identity));
                } else {
                    $saleOrder = StoreSaleOrderJob::dispatchNow($identity, [$productData]);
                    $notDeletedSalesOrders[] = $saleOrder->id;
                }
            } else {
                $saleOrder = StoreSaleOrderJob::dispatchNow($identity, [$productData]);
                $notDeletedSalesOrders[] = $saleOrder->id;
            }

        }


        $this->purchaseOrderProduct->relatedSaleOrdersProducts()->whereNotIn('sale_order_id', $notDeletedSalesOrders)->delete();
        SaleOrder::whereIn('id', $this->purchaseOrderProduct->relatedSaleOrdersProducts()->whereNotIn('sale_order_id', $notDeletedSalesOrders)->pluck('sale_order_id')->toArray())->delete();
    }
}
