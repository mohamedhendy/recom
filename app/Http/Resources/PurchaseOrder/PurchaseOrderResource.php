<?php

namespace App\Http\Resources\PurchaseOrder;

use App\Http\Resources\Supplier\SupplierResource;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property PurchaseOrder $resource
 */
class PurchaseOrderResource extends JsonResource
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
            'supplier' => new SupplierResource($this->resource->supplier),
        ]);
    }
}
