<?php

namespace App\Http\Resources\ArticleIdentity;

use App\Http\Resources\Article\ArticleResource;
use App\Models\ArticleIdentity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ArticleIdentity $resource
 */
class ArticleIdentityResource extends JsonResource
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
                'id' => $this->resource->identity->id,
                'name' => $this->resource->identity->name,
                'number' => $this->resource->identity->number,
            ],
            'article' => new ArticleResource($this->resource->article),
//            'project' => new ProjectResource($this->resource->project),
        ]);
    }
}
