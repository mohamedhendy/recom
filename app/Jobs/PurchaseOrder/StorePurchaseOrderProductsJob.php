<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StorePurchaseOrderProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $purchaseOrder, $productsData;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param array         $productsData
     */
    public function __construct(PurchaseOrder $purchaseOrder, $productsData = [])
    {
        //
        $this->purchaseOrder = $purchaseOrder;
        $this->productsData = collect($productsData);
    }

    /**
     * Execute the job.
     *
     * @return array
     */
    public function handle(): array
    {
        $productsEntities = [];
        foreach ($this->productsData as $key => $data) {
            $productsEntities[] = CreatePurchaseOrderProductJob::dispatchNow($this->purchaseOrder, $data, $key);
        }
        return $productsEntities;
    }
}
