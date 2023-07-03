<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\HasDocument;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('PurchaseOrder/PurchaseOrdersIndexPage', [
            'not_received' => PurchaseOrderProduct::whereRaw('quantity > delivered_quantity')->count(),
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('PurchaseOrder/CreatePurchaseOrderPage', [
            'screen' => [ // Please do not remove this. This will be required at a later stage.
                'labels' => [
                    'select_new_product' => __('purchase_orders.select_new_product'),
                    'related_sales_orders' => __('purchase_orders.related_sales_orders'),
                    'customers' => __('customers'),
                    'choose_client' => __('purchase_orders.choose_client'),
                ]
            ]
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param PurchaseOrder $purchaseOrder
     * @return Response
     */
    public function show(PurchaseOrder $purchaseOrder): Response
    {
        return Inertia::render('PurchaseOrder/ShowPurchaseOrderPage', [
            'entity' => $purchaseOrder->load(
                [
                    'purchaseOrderProducts.product',
                    'purchaseOrderProducts.assets' => function ($query) {
                        return $query->stockAssets();
                    },
                    'purchaseOrderProducts.documents.document',
                    'purchaseOrderProducts.relatedSaleOrdersProducts.saleOrder.identity',
                    'purchaseOrderProducts.relatedSaleOrdersProducts.assets',
                ]
            )
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PurchaseOrder $purchaseOrder
     * @return Response
     */
    public function edit(PurchaseOrder $purchaseOrder): Response
    {

        return Inertia::render('PurchaseOrder/EditPurchaseOrderPage', [
            'entity' => $purchaseOrder->load(
                'purchaseOrderProducts.product',
                'purchaseOrderProducts.assets',
                'purchaseOrderProducts.documents.document',
                'purchaseOrderProducts.relatedSaleOrdersProducts.saleOrder.identity',
                'purchaseOrderProducts.relatedSaleOrdersProducts.assets',

            )
        ]);
    }


    public function documents(PurchaseOrder $purchaseOrder)
    {
        return Inertia::render('PurchaseOrder/UploadDocuments', [
            'purchaseOrder' => $purchaseOrder->load('purchaseOrderProducts.documents.document','purchaseOrderProducts.product')
        ]);
    }

    /**
     * @param PurchaseOrderProduct $purchaseOrderProduct
     * @param SaleOrderProduct $saleOrderProduct
     * @return Response
     */
    public function updateDeliveryStatus(PurchaseOrderProduct $purchaseOrderProduct, SaleOrderProduct $saleOrderProduct)
    {
        return Inertia::render("PurchaseOrder/UpdatePurchaseOrderProductDeliveryStatusPage", [
            'purchaseOrderProductRelatedSales' => $purchaseOrderProduct->relatedSaleOrdersProducts()->with('saleOrder.identity')->get(),
            'purchaseOrderProduct' => $purchaseOrderProduct->load('product'),
            'purchaseOrder' => $purchaseOrderProduct->purchaseOrder,
            'relatedPurchaseOrderProducts' => SaleOrderProduct::whereIn('purchase_order_product_id',
                $purchaseOrderProduct
                    ->purchaseOrder
                    ->purchaseOrderProducts()->pluck('id')->toArray()
            )->where(
                'purchase_order_product_id', '!=', $purchaseOrderProduct->id
            )->with('purchaseOrderProduct.product', 'saleOrder.identity', 'product')->get()
        ]);
    }

}
