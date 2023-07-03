<?php

namespace App\Jobs\PurchaseOrder;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UpdatePurchaseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    /**
     * @var PurchaseOrder
     */
    private $purchaseOrder;

    /**
     * Create a new job instance.
     *
     * @param PurchaseOrder $purchaseOrder
     * @param array $data
     */
    public function __construct(PurchaseOrder $purchaseOrder,$data = [])
    {
        $this->data = collect($data);
        $this->purchaseOrder = $purchaseOrder;
    }

    /**
     * Execute the job.
     *
     * @return PurchaseOrder
     */
    public function handle()
    {
        $user = Auth::user();

         $this->purchaseOrder->update([
             'updated_by_id' => $user->id,
            "internal_id" => $this->data->get('internal_id'),
            "supplier_id" => $this->data->get('supplier_id'),
            "due_date" => $this->data->get('due_date'),
            "issue_date" => $this->data->get('issue_date'),
        ]);
         UpdatePurchaseOrderProductsJob::dispatchNow($this->purchaseOrder, $this->data->get('products'));
        return $this->purchaseOrder->fresh();
    }

}
