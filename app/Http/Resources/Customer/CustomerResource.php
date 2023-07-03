<?php

namespace App\Http\Resources\Customer;

use App\Http\Resources\User\UserResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Customer $resource
 */
class CustomerResource extends JsonResource
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
            'name' => $this->resource->name,
            'number' => $this->resource->number,
            "type" => $this->resource->type,
            'created_at' => $this->resource->created_at,
            'created_by' => $this->whenLoaded('createdBy', function () {
                return new UserResource($this->resource->createdBy);
            }),
            'updated_by' => $this->whenLoaded('updatedBy', function () {
                return new UserResource($this->resource->updatedBy);
            }),
        ];
    }
}
