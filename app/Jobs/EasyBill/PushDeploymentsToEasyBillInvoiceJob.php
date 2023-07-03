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
use Illuminate\Validation\ValidationException;

class PushDeploymentsToEasyBillInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $articleIdentitiesList,$easyBillInvoiceId;
    /**
     * @var PurchaseOrderProduct
     */
    private $saleOrderProduct;
    /**
     * @var array
     */
    private $deployments;

    /**
     * Create a new job instance.
     *
     * @param $easyBillInvoiceId
     * @param SaleOrderProduct $saleOrderProduct
     * @param array $deployments
     */
    public function __construct($easyBillInvoiceId,SaleOrderProduct $saleOrderProduct,$deployments = [])
    {
        //$
        $this->easyBillInvoiceId = $easyBillInvoiceId;
        $this->saleOrderProduct = $saleOrderProduct;
        $this->deployments = $deployments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

            $easyBilldraftInvoice = app('EasyBill')->request('GET', 'documents/' . $this->easyBillInvoiceId);

            if (!$easyBilldraftInvoice) {
                throw ValidationException::withMessages(
                    [
                        'draft_invoice_id' => 'Invalid draft invoice id.',
                    ]
                );
            }

            $attachedItems = ReturnDraftInvoiceItemsJob::dispatchNow($this->saleOrderProduct,$this->deployments,$easyBilldraftInvoice);

            return app('EasyBill')->request(
                'PUT',
                'documents/' . $this->easyBillInvoiceId,
                [
                    'items' => $attachedItems,
                ]
            );

    }
}
