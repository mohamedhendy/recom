<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Inventory\FetchInventoriesRequest;
use App\Http\Requests\Inventory\StoreInventoryAdjustmentRequest;
use App\Http\Resources\Inventory\InventoryProductCollection;
use App\Models\InventoryProduct;
use App\Models\WarehouseProduct;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchInventoriesRequest $request
     * @return InventoryProductCollection
     */
    public function index(FetchInventoriesRequest $request): InventoryProductCollection
    {
        return new InventoryProductCollection($request->getData());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInventoryAdjustmentRequest $request
     * @return mixed
     */
    public function store(StoreInventoryAdjustmentRequest $request)
    {
        return $request->store();
    }

    /**
     * Display the specified resource.
     *
     * @param InventoryProduct $inventoryProduct
     * @return Response
     */
    public function show(InventoryProduct $inventoryProduct): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request          $request
     * @param InventoryProduct $inventoryProduct
     * @return Response
     */
    public function update(Request $request, InventoryProduct $inventoryProduct): Response
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param InventoryProduct $inventoryProduct
     * @return Response
     */
    public function destroy(InventoryProduct $inventoryProduct): Response
    {
        //
    }

    /**
     * @param FetchInventoriesRequest $request
     * @return Response
     */
    // Generate PDF
    public function createPDF(FetchInventoriesRequest $request): Response
    {

        // retreive all records from db
        $inventoryProducts = WarehouseProduct::where('available_qty', '>', 0)->get();

        $pdf = PDF::loadView('inventory_view', compact('inventoryProducts'));

        // download PDF file with download method
        return $pdf->download('inventory.pdf');
    }
}
