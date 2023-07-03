<?php

namespace App\Http\Resources\WarehouseTransaction;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\WarehouseProduct\WarehouseProductResource;
use App\Models\WarehouseTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property WarehouseTransaction $resource
 */
class WarehouseTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->resource->id,
            'quantity' => $this->resource->qty,
            'available_qty' => $this->resource->available_qty,
            'unit_cost' => $this->resource->unit_cost,
            'stock_amount' => $this->resource->stock_amount,
            'transaction_type' => $this->resource->transaction_type,
            'warehouse_product' => new WarehouseProductResource($this->resource->warehouseProduct),
            'created_at' => $this->resource->created_at,
            'created_by' => $this->whenLoaded('createdBy', function () {
                return new UserResource($this->resource->createdBy);
            }),
        ];
    }
}
