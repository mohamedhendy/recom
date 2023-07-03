<?php

namespace App\Http\Resources\SaleOrderProduct;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\PurchaseOrderProduct\PurchaseOrderProductResource;
use App\Models\SaleOrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property SaleOrderProduct $resource
 */
class SaleOrderProductResource extends JsonResource
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
        return array_merge(parent::toArray($request), [
            'identity' => [
                'id' => $this->resource->saleOrder->identity->id,
                'name' => $this->resource->saleOrder->identity->name,
                'number' => $this->resource->saleOrder->identity->number,
            ],
            'sale_order' => $this->whenLoaded('saleOrder', function () {
                return $this->resource->saleOrder;
            }),
            'documents' => $this->resource->documents,
            'purchase_order_product' => new PurchaseOrderProductResource($this->resource->purchaseOrderProduct),
            'product' => new ProductResource($this->resource->product),

        ]);
    }
}
