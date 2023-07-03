<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\Customer;
use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreatePurchaseOrderProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    /**
     * @var PurchaseOrder
     */
    private $purchaseOrder;
    /**
     * @var int
     */
    private $key;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param               $data
     * @param int $key
     */
    public function __construct(PurchaseOrder $purchaseOrder, $data, $key = 0)
    {
        $this->data = collect($data);
        $this->purchaseOrder = $purchaseOrder;
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return Model
     * @throws Throwable
     */
    public function handle()
    {
        $quantity = (float)$this->data->get('quantity', 0);
        $price = (float)$this->data->get('price', 0);

        $relatedSalesOrdersProducts = $this->addStockCustomerToRelatedSalesOrdersProducts($this->data->get('related_sale_orders_products'), $quantity);
        $this->validateQuantity($relatedSalesOrdersProducts, $quantity);
        $purchaseOrderProduct = $this->purchaseOrder->purchaseOrderProducts()->create([
            'created_by_id' => $this->purchaseOrder->created_by_id,
            'product_id' => $this->data->get('product_id', null),
            'price' => $price,
            'currency' => 'EUR',
            'quantity' => $quantity,
        ]);

        CreatePurchaseOrderProductRelatedSalesOrdersJob::dispatchNow($purchaseOrderProduct, $relatedSalesOrdersProducts);
        return $purchaseOrderProduct;
    }

    private function addStockCustomerToRelatedSalesOrdersProducts($relatedProducts = [], $quantity)
    {
        $relatedSalesOrdersCollection = collect($relatedProducts);
        $registedStockQuantity = (float)$relatedSalesOrdersCollection->sum('quantity');
        $unAssignedQuantity = $quantity - $registedStockQuantity;
        throw_if($unAssignedQuantity < 0, ValidationException::withMessages(
            [
                "products.{$this->key}.quantity" => ["quantity should be geater or equal to the customers quantity"]
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

    /**
     * @param array $relatedProducts
     * @param $quantity
     * @throws Throwable
     */
    public function validateQuantity($relatedProducts = [], $quantity)
    {
        $stockAndCustomersAndStaffsAmount = (float)collect($relatedProducts)->sum('quantity');
        throw_if($stockAndCustomersAndStaffsAmount !== (float)$quantity, ValidationException::withMessages(
            [
                "products.{$this->key}.quantity" => ["quantity should be geater or equal to the customers quantity"]
            ]
        ));

    }
}
