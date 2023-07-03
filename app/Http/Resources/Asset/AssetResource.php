<?php

namespace App\Http\Resources\Asset;

use App\Http\Resources\Deployment\DeploymentCollection;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\PurchaseOrderProduct\PurchaseOrderProductResource;
use App\Http\Resources\SaleOrderProduct\SaleOrderProductResource;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Asset $resource
 */
class AssetResource extends JsonResource
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
            'a_number' => $this->resource->a_number,
            'serial_number' => $this->resource->serial_number,
            'description' => $this->resource->description,
            'product_id' => $this->resource->product_id,
            'product' => $this->whenLoaded('product', function () {
                return new ProductResource($this->resource->product);
            }),
            'sale_order_product_id' => $this->resource->sale_order_product_id,
            'saleOrderProduct' => $this->whenLoaded('saleOrderProduct', function () {
                return new SaleOrderProductResource($this->resource->saleOrderProduct);
            }),
            'purchase_order_product_id' => $this->resource->purchase_order_product_id,
            'purchaseOrderProduct' => $this->whenLoaded('purchaseOrderProduct', function () {
                return new PurchaseOrderProductResource($this->resource->purchaseOrderProduct);
            }),
            'deployments' => $this->whenLoaded('deployments', function () {
                return new DeploymentCollection($this->resource->deployments);
            }),
        ];
    }
}
