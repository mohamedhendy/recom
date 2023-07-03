<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarehouseTransaction\FetchWarehouseTransactionsRequest;
use App\Http\Resources\WarehouseTransaction\WarehouseTransactionCollection;

class WarehouseTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchWarehouseTransactionsRequest $request
     * @return WarehouseTransactionCollection
     */
    public function index(FetchWarehouseTransactionsRequest $request): WarehouseTransactionCollection
    {
        return new WarehouseTransactionCollection($request->getData());
    }
}
