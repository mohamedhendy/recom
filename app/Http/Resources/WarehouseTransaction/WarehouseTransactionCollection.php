<?php

namespace App\Http\Resources\WarehouseTransaction;

use App\Models\Product;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseTransactionCollection extends ResourceCollection
{
    public $collects = WarehouseTransactionResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }


    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param $request
     * @return array
     */
    public function with($request): array
    {
        $meta = [];
        if ($request->has('warehouse_product_id') && $request->filled('warehouse_product_id')) {
            $warehouseProduct = WarehouseProduct::find((int)$request->input('warehouse_product_id'));
            if ($warehouseProduct) {
                $meta =
                    [
                        'unit_cost' => $warehouseProduct->unit_cost ,
                        'available_qty' => $warehouseProduct->available_qty,
                        'stock_amount' => $warehouseProduct->unit_cost * $warehouseProduct->available_qty ,

                    ];
            }
        }


        if ($meta == []) {
            $meta =
                [
                    'stock_amount' => WarehouseProduct::sum('stock_amount'),
                    'products_count' => Product::count()
                ];
        }

        return [
            'meta' => $meta,
        ];
    }
}
