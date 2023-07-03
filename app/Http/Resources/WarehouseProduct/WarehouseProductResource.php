<?php

namespace App\Http\Resources\WarehouseProduct;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Warehouse\WarehouseResource;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property WarehouseProduct $resource
 */
class WarehouseProductResource extends JsonResource
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
        // return
        return [
            'id' => $this->resource->id,
            'product' => new ProductResource($this->resource->product),
            'warehouse' => new WarehouseResource($this->resource->warehouse),
            // 'id' => $this->resource->id,
            // 'id' => $this->resource->id,
            // parent::toArray($request)
        ];
    }
}
