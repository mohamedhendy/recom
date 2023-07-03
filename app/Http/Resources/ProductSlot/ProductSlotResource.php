<?php

namespace App\Http\Resources\ProductSlot;

use App\Http\Resources\DeploymentSlot\DeploymentSlotCollection;
use App\Http\Resources\Product\ProductResource;
use App\Models\ProductSlot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProductSlot $resource
 */
class ProductSlotResource extends JsonResource
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
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'number' => $this->resource->number,
            'product_id' => $this->resource->product_id,
            'product' => $this->whenLoaded('product', function () {
                return new ProductResource($this->resource->product);
            }),
            'default_info' => $this->resource->default_info,
            'deploymentSlots' => $this->whenLoaded('deploymentSlots', function () {
                return new DeploymentSlotCollection($this->resource->deploymentSlots);
            }),

        ];
    }
}
