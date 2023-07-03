<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use App\Models\WarehouseTransaction;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Warehouse/Index", [
            'warehouses_count' => Warehouse::count(),
            'transactions_count' => WarehouseTransaction::count(),
            'warehouses_total_amount' => WarehouseProduct::sum('stock_amount'),
            'products_count' => Product::count(),
        ]);
    }
}
