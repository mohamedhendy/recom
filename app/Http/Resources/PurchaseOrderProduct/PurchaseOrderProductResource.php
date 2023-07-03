<?php

namespace App\Http\Resources\PurchaseOrderProduct;

use App\Http\Resources\PurchaseOrder\PurchaseOrderResource;
use App\Models\PurchaseOrderProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PurchaseOrderProduct $resource
 */
class PurchaseOrderProductResource extends JsonResource
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
            'product' => $this->resource->product,
            'documents' => $this->resource->documents,
            'purchase_order' => new PurchaseOrderResource($this->resource->purchaseOrder),
        ]);
    }
}
