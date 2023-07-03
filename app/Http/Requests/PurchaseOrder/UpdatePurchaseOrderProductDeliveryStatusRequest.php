<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Jobs\Asset\CreateAssetsJob;
use App\Jobs\PurchaseOrder\AddPurchaseOrderProductDeliveredQuantityJob;
use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseOrderProductDeliveryStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'related_sales' => 'required|array',
            "related_sales.*.id" => 'required|integer|exists:sale_order_products,id',
            "related_sales.*.delivery_status" => 'required|array',
            'related_sales.*.delivery_status.received_quantity' => 'required|integer|min:0',
            'related_sales.*.delivery_status.assets' => 'nullable|array',
            'related_sales.*.delivery_status.assets.*.serial_number' => 'nullable|string',
            'related_sales.*.delivery_status.assets.*.description' => 'nullable|string',
            'related_sales.*.delivery_status.assets.*.a_number' => 'nullable|string',
        ];
    }

    public function update(PurchaseOrderProduct $purchaseOrderProduct,SaleOrderProduct $saleOrderProduct,$deliveredQuantity,
                           $assets = [])
    {
        return DB::transaction(function () use ($purchaseOrderProduct,$saleOrderProduct,$deliveredQuantity,$assets) {
            // 1- UPDATE DELIVERY STATUS COLUMN IN PURCHASE_ORDER_PRODUCTS
            AddPurchaseOrderProductDeliveredQuantityJob::dispatchNow($purchaseOrderProduct,$saleOrderProduct, $deliveredQuantity);
            // 2- CREATE WAREOHOUSE TRANSACTIONS AND UPDATE AVAILABLE QUANTITY
            CreateProductWareHouseTransactionJob::dispatchNow(null, $purchaseOrderProduct->product, $purchaseOrderProduct, $deliveredQuantity, 'debit', auth()->user());
            // 3- INSERT DATA TO ASSETS TABLE
            CreateAssetsJob::dispatchNow($assets, $purchaseOrderProduct->product, $purchaseOrderProduct->id,$saleOrderProduct->id);
        });
    }
}
