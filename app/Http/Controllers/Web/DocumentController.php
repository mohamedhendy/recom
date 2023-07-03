<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Inertia\Inertia;
use Inertia\Response;

class DocumentController extends Controller
{
    //

    /**
     * @param PurchaseOrderProduct $purchaseOrderProduct
     * @return Response
     */
    public function updatePurchaseOrderProduct(PurchaseOrderProduct $purchaseOrderProduct): Response
    {
        return Inertia::render(
            'Documents/UpdateDocumentsPage', [
            'entity' => $purchaseOrderProduct->load('documents.document'),
            'entity_id' => $purchaseOrderProduct->id,
            'entity_type' => 'App\\Models\\PurchaseOrderProduct'
        ]);
    }

    /**
     * @param SaleOrderProduct $saleOrderProduct
     * @return Response
     */
    public function updateSaleOrderProduct(SaleOrderProduct $saleOrderProduct): Response
    {
        return Inertia::render(
            'Documents/UpdateDocumentsPage', [
            'entity' => $saleOrderProduct->load('documents.document'),
            'entity_id' => $saleOrderProduct->id,
            'entity_type' => 'App\\Models\\SaleOrderProduct'
        ]);
    }
}
