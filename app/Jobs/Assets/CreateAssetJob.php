<?php

namespace App\Jobs\Assets;

use App\Models\Asset;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAssetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $asset;
    /**
     * @var null
     */
    private $purchaseOrderProductId;
    /**
     * @var null
     */
    private $saleOrderProductId;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var null
     */
    private $inventoryProductId;

    /**
     * Create a new job instance.
     *
     * @param $asset
     * @param Product $product
     * @param null $purchaseOrderProductId
     * @param null $saleOrderProductId
     * @param null $inventoryProductId
     */
    public function __construct($asset, Product $product, $purchaseOrderProductId = null, $saleOrderProductId = null, $inventoryProductId = null)
    {
        //
        $this->asset = collect($asset);
        $this->purchaseOrderProductId = $purchaseOrderProductId;
        $this->saleOrderProductId = $saleOrderProductId;
        $this->product = $product;
        $this->inventoryProductId = $inventoryProductId;
    }

    /**
     * Execute the job.
     *
     * @return Asset|Asset[]|Collection|Model|void
     */
    public function handle()
    {
        $assetEntity = new Asset;
        $assetEntity->created_by_id = auth()->id();
        $assetEntity->serial_number = $this->asset->get('serial_number');
        $assetEntity->a_number = $this->asset->get('a_number');
        $assetEntity->description = $this->asset->get('description');
        $assetEntity->product_id = $this->product->id;
        $assetEntity->purchase_order_product_id = $this->purchaseOrderProductId;
        $assetEntity->sale_order_product_id = $this->saleOrderProductId;
        $assetEntity->inventory_product_id = $this->inventoryProductId;
        $assetEntity->save();

        return Asset::find($assetEntity->id);
    }
}
