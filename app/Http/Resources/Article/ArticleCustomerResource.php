<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\Customer\CustomerResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Project $resource
 */
class ArticleCustomerResource extends JsonResource
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
            'customer' => new CustomerResource($this->resource->customer),
//            'article' => new ArticleResource($this->resource->article)
//            'project' => new ProjectResource($this->resource->project),
        ]);
    }
}
