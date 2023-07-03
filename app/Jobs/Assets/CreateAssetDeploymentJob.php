<?php

namespace App\Jobs\Assets;

use App\Models\Asset;
use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class CreateAssetDeploymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Asset
     */
    private $asset;
    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;

    /**
     * Create a new job instance.
     *
     * @param Asset $asset
     * @param SaleOrderProduct $saleOrderProduct
     */
    public function __construct(Asset $asset, SaleOrderProduct $saleOrderProduct)
    {
        //
        $this->asset = $asset;
        $this->saleOrderProduct = $saleOrderProduct;
    }

    /**
     * Execute the job.
     *
     * @return Model
     */
    public function handle()
    {
        //
        $this->asset->update([
            'is_deployed' => true
        ]);
        return $this->saleOrderProduct->saleOrder->identity->deployments()->create([
            'identity_id' => $this->saleOrderProduct->saleOrder->identity_id,
            'identity_type' => $this->saleOrderProduct->saleOrder->identity_type,
            'asset_id' => $this->asset->id,
            'info' => $this->saleOrderProduct->product->default_info,
            'created_by_id' => Auth::id(),
        ]);

    }
}
