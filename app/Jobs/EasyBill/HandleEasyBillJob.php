<?php

namespace App\Jobs\EasyBill;

use App\Models\SaleOrderProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class HandleEasyBillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $markAsBilled, $articleIdentities, $draftInvoiceId, $createNewDraft;
    /**
     * @var SaleOrderProduct
     */
    private $saleOrderProduct;
    /**
     * @var array
     */
    private $deployments;

    /**
     * Create a new job instance.
     *
     * @param bool $markAsBilled
     * @param SaleOrderProduct $saleOrderProduct
     * @param array $deployments
     * @param int $draftInvoiceId
     * @param bool $createNewDraft
     */
    public function __construct($markAsBilled = false, SaleOrderProduct $saleOrderProduct,$deployments = [], $draftInvoiceId = 0, $createNewDraft = false)
    {
        //
        $this->markAsBilled = $markAsBilled;
        $this->draftInvoiceId = $draftInvoiceId;
        $this->createNewDraft = $createNewDraft;
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
        if ($this->markAsBilled || (!$this->createNewDraft && !$this->draftInvoiceId)) {
        } else {
            if ($this->createNewDraft) {
                CreateNewEasyBillDraftJob::dispatchNow($this->saleOrderProduct,$this->deployments, $this->getItemsCurrency());
            } else {
                PushDeploymentsToEasyBillInvoiceJob::dispatchNow($this->draftInvoiceId,$this->saleOrderProduct ,$this->deployments);
            }
        }
    }

    private function getItemsCurrency(): string
    {

        return 'EUR';
    }
}
