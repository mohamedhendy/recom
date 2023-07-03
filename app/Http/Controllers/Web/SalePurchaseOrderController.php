<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SaleOrderProduct;
use Inertia\Inertia;
use Inertia\Response;

class SalePurchaseOrderController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {

        return Inertia::render('SalePurchaseOrders/SalePurchaseOrdersIndexPage', [
            'not_received' => SaleOrderProduct::notReceived()->count(),
            'not_billed' => SaleOrderProduct::notStock()->notBilled()->count(),
        ]);
    }

}
