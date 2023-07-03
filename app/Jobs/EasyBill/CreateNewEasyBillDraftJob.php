<?php

namespace App\Jobs\EasyBill;

use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNewEasyBillDraftJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $articleIdentities,$currency;
    /**
     * @var PurchaseOrderProduct
     */
    /**
     * @var array
     */
    private $deployments;
    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;

    /**
     * Create a new job instance.
     *
     * @param SaleOrderProduct $saleOrderProduct
     * @param array $deployments
     * @param string $currency
     */
    public function __construct(SaleOrderProduct $saleOrderProduct,$deployments = [],$currency = "")
    {
        $this->currency = $currency;
        $this->deployments = $deployments;
        $this->saleOrderProduct = $saleOrderProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invoice =  app('EasyBill')->request(
            'POST',
            'documents',
            [
                'type' => 'INVOICE',
                'currency' => $this->currency,
                'items' => ReturnDraftInvoiceItemsJob::dispatchNow($this->saleOrderProduct,$this->deployments)
            ]
        );


        return $invoice;
    }
}
