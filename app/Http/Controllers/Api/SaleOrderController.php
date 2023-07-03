<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleOrder\StoreSaleOrderRequest;
use App\Http\Requests\SaleOrder\UpdateSaleOrderBillingStatusRequest;
use App\Http\Requests\SaleOrder\FetchSaleOrdersRequest;
use App\Http\Resources\SaleOrderProduct\SaleOrderProductCollection;
use App\Models\SaleOrderProduct;


class SaleOrderController extends Controller
{
    /**
     * @param FetchSaleOrdersRequest $request
     * @return SaleOrderProductCollection
     */
    public function index(FetchSaleOrdersRequest $request): SaleOrderProductCollection
    {
        return new SaleOrderProductCollection($request->getData());
    }

    /**
     * @param StoreSaleOrderRequest $request
     * @return mixed
     */
    public function store(StoreSaleOrderRequest $request)
    {
        return $request->store();
    }

    public function updateBilling(SaleOrderProduct $saleOrderProduct, UpdateSaleOrderBillingStatusRequest $request)
    {
        return $request->push($saleOrderProduct);
    }
}
