<?php

namespace App\Http\Resources\EasyBill;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EasyBillInvoiceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toArray($request): array
    {
        return [
            'data' => $this['items'],
        ];
    }

    public function with($request): array
    {
        return [
            'meta' => [
                'version' => config('app.version'),
                'message' => 'easybill invoices has been loaded'
            ],

        ];
    }
}
