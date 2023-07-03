<?php

namespace App\Http\Resources\Article;

use App\Http\Resources\Staff\StaffResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class ArticleStaffResource extends JsonResource
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
            'staff' => new StaffResource($this->resource->staff),
        ]);
    }
}
