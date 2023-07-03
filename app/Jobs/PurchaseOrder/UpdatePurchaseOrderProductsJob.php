<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\Customer;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

class UpdatePurchaseOrderProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $purchaseOrder, $productsData;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param array         $productsData
     */
    public function __construct(PurchaseOrder $purchaseOrder, $productsData = [])
    {
        //
        $this->purchaseOrder = $purchaseOrder;
        $this->productsData = collect($productsData);
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $updatedProducts = [];
        $productsEntities = [];
        foreach ($this->productsData as $key => $data) {
            $data = collect($data);
            $quantity = (float)$data->get('quantity', 0);
            $price = (float)$data->get('price', 0);
            if ($data->has('id')) {
                $productId = $data->get('id');
                $purchaseOrderProduct = PurchaseOrderProduct::find($productId);
                if ($purchaseOrderProduct) {
                    $relatedSalesOrdersProducts = $this->addStockCustomerToRelatedSalesOrdersProducts($data->get('related_sale_orders_products'), $quantity,$key);
                    $this->validateQuantity($relatedSalesOrdersProducts, $quantity,$key);
                    PurchaseOrderProduct::where('id', $productId)->update([
                        'updated_by_id' => $this->purchaseOrder->updated_by_id,
                        'product_id' => $data->get('product_id', null),
                        'price' => $price,
                        'currency' => 'EUR',
                        'quantity' => $quantity,
                    ]);
                    UpdatePurchaseOrderProductAssetsJob::dispatchNow($purchaseOrderProduct, $data->get('assets'));
                    UpdatePurchaseOrderProductRelatedSalesOrdersJob::dispatchNow($purchaseOrderProduct, $relatedSalesOrdersProducts);

                }

            } else {
                $product = CreatePurchaseOrderProductJob::dispatchNow($this->purchaseOrder, $data, $key);
                $productsEntities[] = $product;
                $productId = $product->id;
            }


            $updatedProducts[] = $productId;
        }
        $this->purchaseOrder->purchaseOrderProducts()->whereNotIn('id', $updatedProducts)->delete();
        return $productsEntities;
    }

    public function validateQuantity($relatedProducts = [], $quantity, $key)
    {
        $stockAndCustomersAndStaffsAmount = (float)collect($relatedProducts)->sum('quantity');
        throw_if($stockAndCustomersAndStaffsAmount !== (float)$quantity, ValidationException::withMessages(
            [
                "products.{$key}.quantity" => ["quantity should be geater or equal to the customers quantity"]
            ]
        ));

    }

    private function addStockCustomerToRelatedSalesOrdersProducts($relatedProducts = [], $quantity,$key)
    {
        $relatedSalesOrdersCollection = collect($relatedProducts);
        $registedStockQuantity = (float)$relatedSalesOrdersCollection->sum('quantity');
        $unAssignedQuantity = $quantity - $registedStockQuantity;
        throw_if($unAssignedQuantity < 0, ValidationException::withMessages(
            [
                "products.{$key}.quantity" => ["quantity should be geater or equal to the customers quantity"]
            ]
        ));

        if ($unAssignedQuantity) {
            $dbStockEntity = Customer::where('type', 'stock')->first();
            if ($dbStockEntity) {
                $relatedSalesOrdersCollection->prepend([
                    "quantity" => $unAssignedQuantity,
                    "discount" => 0,
                    "total" => 0,
                    "price" => 0,
                    "identity_name" => $dbStockEntity->name,
                    "identity_id" => $dbStockEntity->id,
                    "identity_type" => "customer",
                ]);
            }

        }


        return $relatedSalesOrdersCollection;


    }
}
