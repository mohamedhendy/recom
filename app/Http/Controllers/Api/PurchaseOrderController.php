<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseOrder\FetchPurchaseOrdersRequest;
use App\Http\Requests\PurchaseOrder\StorePurchaseOrderRequest;
use App\Http\Requests\PurchaseOrder\UpdateDocumentsRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderProductDeliveryStatusRequest;
use App\Http\Requests\PurchaseOrder\UpdatePurchaseOrderRequest;
use App\Http\Resources\PurchaseOrderProduct\PurchaseOrderProductCollection;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchPurchaseOrdersRequest $request
     * @return PurchaseOrderProductCollection
     */
    public function index(FetchPurchaseOrdersRequest $request): PurchaseOrderProductCollection
    {
        return new PurchaseOrderProductCollection($request->getData());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePurchaseOrderRequest $request
     * @return PurchaseOrder
     */
    public function store(StorePurchaseOrderRequest $request): PurchaseOrder
    {
        return $request->store();
    }


    public function updateDeliveryStatus(purchaseOrderProduct $purchaseOrderProduct, UpdatePurchaseOrderProductDeliveryStatusRequest
    $updatePurchaseOrderProductDeliveryStatusRequest)
    {
        $salesOrderProducts = $updatePurchaseOrderProductDeliveryStatusRequest->input('related_sales');
        $totalQuantity = collect($salesOrderProducts)->sum('delivery_status.received_quantity');
        throw_if($totalQuantity === 0, ValidationException::withMessages([
            'related_sales' => 'No Quantity Received'
        ]));
        foreach ($salesOrderProducts as $salesOrderProduct) {
            $deliveryStatusDetails = collect($salesOrderProduct['delivery_status']);
            $deliveredQuantity = $deliveryStatusDetails->get('received_quantity',null);
            if($deliveredQuantity) {
                $saleOrderProductEntity = SaleOrderProduct::findOrFail($salesOrderProduct['id']);
                $purchaseOrderProductEntity = PurchaseOrderProduct::findOrFail($salesOrderProduct['purchase_order_product_id']);

                $updatePurchaseOrderProductDeliveryStatusRequest->update($purchaseOrderProductEntity,
                    $saleOrderProductEntity,$deliveredQuantity,$deliveryStatusDetails->get('assets',[]));
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePurchaseOrderRequest $request
     * @param PurchaseOrder $purchaseOrder
     * @return Response
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        //
        return $request->update($purchaseOrder);
    }

    public function documents(PurchaseOrder $purchaseOrder,UpdateDocumentsRequest $updateDocumentsRequest)
    {
        return $updateDocumentsRequest->handle($purchaseOrder);
    }


}
