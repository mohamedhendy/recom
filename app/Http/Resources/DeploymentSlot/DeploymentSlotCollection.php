<?php

namespace App\Http\Resources\DeploymentSlot;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DeploymentSlotCollection extends ResourceCollection
{
    public $collects = DeploymentSlotResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
