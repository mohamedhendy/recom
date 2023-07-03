<?php

namespace App\Jobs\Assets;

use App\Models\Asset;
use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeployAssetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;
    /**
     * @var array
     */
    private $assets;

    /**
     * Create a new job instance.
     *
     * @param SaleOrderProduct $saleOrderProduct
     * @param array $assets
     */
    public function __construct(SaleOrderProduct $saleOrderProduct, $assets = [])
    {
        //
        $this->saleOrderProduct = $saleOrderProduct;
        $this->assets = collect($assets);
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle()
    {
        $assetsList = [];
        foreach ($this->assets as $asset) {
            $assetEntity = $this->getAssetEntity($asset);
            if ($assetEntity) {
                $assetEntity->update([
                    'sale_order_product_id' => $this->saleOrderProduct->id
                ]);
            } else {
                $assetEntity = CreateAssetJob::dispatchNow($asset, $this->saleOrderProduct->product, null, $this->saleOrderProduct->id);
            }
            CreateAssetDeploymentJob::dispatchNow($assetEntity, $this->saleOrderProduct);
            $assetsList[] = $assetEntity->toArray();
        }

        return $assetsList;
    }

    private function getAssetEntity($asset)
    {
        $asset = collect($asset);

        if($asset->has('id')) {
            $assetEntity = Asset::find($asset->get('id'));
            if($assetEntity) return $assetEntity;
        }

        $baseConditions = [
            ['product_id', $this->saleOrderProduct->product_id],
        ];

        if ($this->saleOrderProduct->purchase_order_product_id) {
            $baseConditions[] = ['sale_order_product_id', $this->saleOrderProduct->id];
        }
        return Asset::where($baseConditions)->where(function ($query) use ($asset) {
            $query->where(function ($subQuery) use ($asset) {
                $subQuery->where([
                    ['serial_number', $asset->get('serial_number', null)],
                    ['serial_number', '!=', null],
                ]);
            })->orWhere(function ($subQuery) use ($asset) {
                $subQuery->where([
                    ['a_number', $asset->get('a_number', null)],
                    ['a_number', '!=', null],

                ]);
            })->orWhere(function ($subQuery) use ($asset) {
                $subQuery->where([
                    ['description', $asset->get('description', null)],
                    ['description', '!=', null],

                ]);
            });
        })->orderByDesc('id')->first();
    }
}
