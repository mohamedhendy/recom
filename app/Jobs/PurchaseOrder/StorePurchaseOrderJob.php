<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class StorePurchaseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = collect($data);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = Auth::user();

        $creationDate = $this->getCreatedAtDate();
        $purchaseOrder = $user->createdPurchaseOrders()->create([
            "internal_id" => $this->data->get('internal_id'),
            "supplier_id" => $this->data->get('supplier_id'),
            "due_date" => $this->data->get('due_date'),
            "creation_year" => $creationDate,
            "issue_date" => $this->data->get('issue_date'),
            'number' => $creationDate->format("Y") . '-' . PurchaseOrder::nextPurchaseOrderId($creationDate)
        ]);
        StorePurchaseOrderProductsJob::dispatchNow($purchaseOrder, $this->data->get('products'));
        return $purchaseOrder;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAtDate(): Carbon
    {
        $invoiceYear = $this->data->get('invoice_year', null);

        if ($invoiceYear && $invoiceYear != "undefined" && $date = Carbon::createFromDate($invoiceYear)) {
            return $date;
        }


        return Carbon::now();
    }
}
