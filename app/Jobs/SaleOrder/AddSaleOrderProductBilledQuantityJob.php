<?php

namespace App\Jobs\SaleOrder;

use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AddSaleOrderProductBilledQuantityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;
    private $billedQuantity;

    /**
     * Create a new job instance.
     *
     * @param SaleOrderProduct $saleOrderProduct
     * @param $billedQuantity
     */
    public function __construct(SaleOrderProduct $saleOrderProduct, $billedQuantity)
    {
        //
        $this->saleOrderProduct = $saleOrderProduct;
        $this->billedQuantity = $billedQuantity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->saleOrderProduct->update([
            'billed_quantity' => DB::raw("billed_quantity + {$this->billedQuantity}")
        ]);
    }
}
