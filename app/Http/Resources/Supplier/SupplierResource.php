<?php

namespace App\Http\Resources\Supplier;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Supplier $resource
 */
class SupplierResource extends JsonResource
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
            'number' => $this->resource->number,
            'name' => $this->resource->name,
            'vat_id' => $this->resource->vat_id,
            'tax_id' => $this->resource->tax_id,
            'created_at' => $this->resource->created_at
        ];
    }
}
