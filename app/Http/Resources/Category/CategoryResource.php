<?php

namespace App\Http\Resources\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Category $resource
 */
class CategoryResource extends JsonResource
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
            'parent_category' => $this->whenLoaded('parentCategory', function () {
                return new CategoryResource($this->resource->parentCategory);
            })
        ]);
    }
}
