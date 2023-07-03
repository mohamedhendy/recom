<?php

namespace App\Http\Resources\Teamviewer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TeamviewerCollection extends ResourceCollection
{
    public $collects = TeamviewerResource::class;

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
