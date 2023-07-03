<?php

namespace App\Http\Resources\DeploymentSlot;

use App\Http\Resources\Deployment\DeploymentCollection;
use App\Http\Resources\Deployment\DeploymentResource;
use App\Models\DeploymentSlot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property DeploymentSlot $resource
 */
class DeploymentSlotResource extends JsonResource
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
            'info' => $this->resource->info,
            'deployment_id' => $this->resource->deployment_id,
            'deployment' => $this->whenLoaded('deployment', function () {
                return new DeploymentResource($this->resource->deployment);
            }),
            'product_slot_id' => $this->resource->product_slot_id,
            'productSlot' => $this->whenLoaded('productSlot', function () {
                return new DeploymentResource($this->resource->productSlot);
            }),
            'childDeployment' => $this->whenLoaded('childDeployment', function () {
                return new DeploymentCollection($this->resource->childDeployments);
            }),
            'localConnections' => $this->whenLoaded('localConnections', function () {
                return new DeploymentSlotCollection($this->resource->localConnections);
            }),
            'remoteConnections' => $this->whenLoaded('remoteConnections', function () {
                return new DeploymentSlotCollection($this->resource->remoteConnections);
            }),
//            'connections' => $this->whenLoaded('localConnections', function () {
//                return new DeploymentSlotCollection($this->resource->localConnections->merge($this->resource->remoteConnections));
//            }),
        ];
    }
}
