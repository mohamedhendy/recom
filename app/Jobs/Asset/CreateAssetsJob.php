<?php

namespace App\Jobs\Asset;

use App\Jobs\Assets\CreateAssetJob;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateAssetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $assets;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var null
     */
    private $purchaseOrderProductId;
    /**
     * @var null
     */
    private $inventoryProductId;
    /**
     * @var null
     */
    private $saleOrderProductId;

    /**
     * Create a new job instance.
     *
     * @param array $assets
     * @param Product $product
     * @param null $purchaseOrderProductId
     * @param null $inventoryProductId
     * @param null $saleOrderProductId
     */
    public function __construct($assets = [], Product $product, $purchaseOrderProductId = null, $saleOrderProductId = null,$inventoryProductId = null)
    {
        //
        $this->assets = collect($assets);
        $this->product = $product;
        $this->purchaseOrderProductId = $purchaseOrderProductId;
        $this->inventoryProductId = $inventoryProductId;
        $this->saleOrderProductId = $saleOrderProductId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->assets as $asset) {
            CreateAssetJob::dispatchNow($asset,$this->product, $this->purchaseOrderProductId,$this->saleOrderProductId,$this->inventoryProductId);
        }
    }
}
