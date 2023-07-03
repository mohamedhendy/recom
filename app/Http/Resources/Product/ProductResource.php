<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\User\UserResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Product $resource
 */
class ProductResource extends JsonResource
{
    private $arr;

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
            'available_qty' => $this->whenLoaded('warehouseProducts', function () {
                return $this->resource->available_qty;
            }),
            'ean_number' => $this->resource->ean_number,
            'category_id' => $this->resource->category_id,
            'default_sale_price' => $this->resource->default_sale_price,
            'default_purchase_price' => $this->resource->default_purchase_price,
            'manufacturer' => $this->resource->manufacturer,
            'model' => $this->resource->model,
            'default_info' => $this->resource->default_info,
            'category_name' => $this->resource->category ? $this->resource->category->name : "",
            'created_at' => $this->resource->created_at,
            'createdBy' => $this->whenLoaded('createdBy', function () {
                return new UserResource($this->resource->createdBy);
            }),
            'updatedBy' => $this->whenLoaded('updatedBy', function () {
                return new UserResource($this->resource->updatedBy);
            }),
        ];
    }
}
