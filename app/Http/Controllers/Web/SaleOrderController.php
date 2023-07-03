<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\SaleOrder;
use App\Models\SaleOrderProduct;
use Inertia\Inertia;
use Inertia\Response;

class SaleOrderController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("SaleOrders/SaleOrdersIndexPage", [
            'not_billed' => SaleOrderProduct::notStock()->whereRaw('quantity > billed_quantity')->count(),

        ]);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("SaleOrders/CreateSaleOrderPage");
    }

    /**
     * @param SaleOrder $saleOrder
     * @return Response
     */
    public function show(SaleOrder $saleOrder): Response
    {

        return Inertia::render(
            'SaleOrders/ShowSaleOrderPage', [
                'sale_order' => $saleOrder->load(
                    'saleOrderProducts.product', 'saleOrderProducts.assets', 'saleOrderProducts.documents.document'
                )
            ]
        );
    }

    public function updateBilling(SaleOrderProduct $saleOrderProduct)
    {
        return Inertia::render("SaleOrders/UpdateSaleOrderProductBillingStatusPage", [
            'saleOrderProduct' => $saleOrderProduct->load(['purchaseOrderProduct']),
            'assets' => $saleOrderProduct->purchase_order_product_id ? $saleOrderProduct->assets()->notDeployed()->get()
                : $saleOrderProduct->product->assets()->stockAssets()->notDeployed()->get(),
            'product' => (new ProductResource($saleOrderProduct->product->load('warehouseProducts')))->resolve(),
            'saleOrder' => $saleOrderProduct->saleOrder->load('identity'),
            'relatedSaleOrdersProducts' => SaleOrderProduct::whereIn('sale_order_id', $saleOrderProduct->saleOrder->identity->saleOrders()->pluck('id')->toArray())->whereKeyNot($saleOrderProduct->id)->orderBy('id', 'desc')->with('product')->get()
        ]);
    }

}
