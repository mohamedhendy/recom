<?php

namespace App\Http\Resources\Document;

use App\Models\HasDocument;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property HasDocument $resource
 */
class DocumentAbleResource extends JsonResource
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
        return array_merge(parent::toArray($request), ['document' => new DocumentResource($this->resource->document)]);
    }
}
