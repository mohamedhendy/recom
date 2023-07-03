<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\Asset;
use App\Models\PurchaseOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePurchaseOrderProductAssetsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var PurchaseOrderProduct
     */
    private $purchaseOrderProduct;
    private $assets;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrderProduct $purchaseOrderProduct
     * @param $assets
     */
    public function __construct(PurchaseOrderProduct $purchaseOrderProduct,$assets)
    {
        //
        $this->purchaseOrderProduct = $purchaseOrderProduct;
        $this->assets = $assets;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach ($this->assets as $asset){
            $asset = collect($asset);

            if($asset->get('id',null)){
                Asset::where('id',$asset->get('id'))->update([
                    'serial_number' => $asset->get('serial_number'),
                    'a_number' => $asset->get('a_number'),
//                    'description' => $asset->get('description'),
                ]);
            }
        }
    }
}
