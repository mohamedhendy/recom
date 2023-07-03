<?php

namespace App\Jobs\EasyBill;

use App\Models\Article;
use App\Models\ArticleIdentity;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReturnDraftInvoiceItemsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $deployments, $easyBilldraftInvoice;
    /**
     * @var PurchaseOrderProduct
     */
    private $saleOrderProduct;

    /**
     * Create a new job instance.
     *
     * @param SaleOrderProduct $saleOrderProduct
     * @param array            $deployments
     * @param array            $easyBilldraftInvoice
     */
    public function __construct(SaleOrderProduct $saleOrderProduct, $deployments = [], $easyBilldraftInvoice = [])
    {
        //$
        $this->deployments = $deployments;
        $this->easyBilldraftInvoice = $easyBilldraftInvoice;
        $this->saleOrderProduct = $saleOrderProduct;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $existingDraftItems = array_key_exists('items', $this->easyBilldraftInvoice) ? $this->easyBilldraftInvoice['items'] : [];
        $existingDraftItemsCount = count($existingDraftItems);

        $existingDraftItems[] = [
            'number' => $this->saleOrderProduct->id,
            'description' => view('easybill.easybill_item_description', ['deployments' => $this->deployments, 'product' => $this->saleOrderProduct->product])->render(),
            'quantity' => count($this->deployments),
            'quantity_str' => count($this->deployments),
            'position' => $existingDraftItemsCount,
            'type' => 'POSITION',
            'single_price_net' => $this->saleOrderProduct->price * 100,
            'cost_price_net' => $this->saleOrderProduct->purchaseOrder ? $this->saleOrderProduct->purchaseOrder->price * 100: $this->saleOrderProduct->product->unit_cost_average * 100,
            "itemType" => "UNDEFINED",
            'vat_percent' => config('app.content.tax_percent'),
        ];

        return $existingDraftItems;
    }
}
