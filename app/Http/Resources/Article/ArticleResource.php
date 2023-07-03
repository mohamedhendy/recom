<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\Supplier\SupplierResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Article $resource
 */
class ArticleResource extends JsonResource
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
        $data = parent::toArray($request);

        return array_merge($data, [
            'supplier' => new SupplierResource($this->resource->invoice->supplier),
            'invoice_number' => $this->resource->invoice->number,
            // 'documents' => $this->resource->documents,
            'product' => $this->resource->product

        ]);
    }
}
