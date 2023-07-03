<?php

namespace App\Http\Resources\Deployment;

use App\Http\Resources\Asset\AssetResource;
use App\Http\Resources\DeploymentSlot\DeploymentSlotCollection;
use App\Http\Resources\ProductSlot\ProductSlotCollection;
use App\Http\Resources\User\UserResource;
use App\Models\Deployment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Deployment $resource
 */
class DeploymentResource extends JsonResource
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
            'asset_id' => $this->resource->asset_id,
            'asset' => $this->whenLoaded('asset', function () {
                return new AssetResource($this->resource->asset);
            }),
            'o_number' => $this->resource->o_number,
            'name' => $this->resource->name,
            'type' => $this->resource->type,
            'address' => $this->resource->address,
            'building' => $this->resource->building,
            'room' => $this->resource->room,
            'exact_position' => $this->resource->exact_position,
            'info' => $this->resource->info,
            'contact' => $this->resource->contact,
            'deployed_slot_id' => $this->resource->deployed_slot_id,
            'deployedSlot' => $this->whenLoaded('deployedSlot', function () {
                return new DeploymentResource($this->resource->deployedSlot);
            }),
            'created_at' => $this->resource->created_at,
            'created_by' => $this->whenLoaded('createdBy', function () {
                return new UserResource($this->resource->createdBy);
            }),
            'updated_by' => $this->whenLoaded('updatedBy', function () {
                return new UserResource($this->resource->updatedBy);
            }),
            'deploymentSlots' => $this->whenLoaded('deploymentSlots', function () {
                return new DeploymentSlotCollection($this->resource->deploymentSlots);
            }),
            'productSlots' => $this->whenLoaded('productSlots', function () {
                return new ProductSlotCollection($this->resource->productSlots);
            }),
        ];
    }
}
