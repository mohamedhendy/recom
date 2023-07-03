<?php

namespace App\Jobs\SaleOrder;

use App\Models\SaleOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreSaleOrderProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $saleOrder, $productsData;

    /**
     * Create a new job instance.
     *
     * @param SaleOrder $saleOrder
     * @param array $productsData
     */
    public function __construct(SaleOrder $saleOrder, $productsData = [])
    {
        //
        $this->saleOrder = $saleOrder;
        $this->productsData = collect($productsData);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productsEntities = [];


        foreach ($this->productsData as $product) {
            $salesOrderProduct = collect($product);

            $productsEntities[] = $this->saleOrder->saleOrderProducts()->create([
                'created_by_id' => $this->saleOrder->created_by_id,
                'currency' => 'EUR',
                'quantity' => $salesOrderProduct->get('quantity', 0),
                'price' => $salesOrderProduct->get('price', 0),
                'unit_cost' => $salesOrderProduct->get('quantity', 0),
                'purchase_order_product_id' => $salesOrderProduct->get('purchase_order_product_id', null),
                'product_id' => $salesOrderProduct->get('product_id', null),
            ]);
        }


        return $productsEntities;
    }
}
