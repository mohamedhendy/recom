<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Jobs\PurchaseOrder\UpdatePurchaseOrderJob;
use App\Models\PurchaseOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdatePurchaseOrderRequest extends FormRequest
{
    use PurchaseOrderValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    public function update(PurchaseOrder $purchaseOrder)
    {
        return DB::transaction(function () use ($purchaseOrder) {
            $data = $this->only('internal_id', 'products', 'supplier_id', 'due_date', 'issue_date', 'invoice_year');
            $updatedPurchaseOrder = UpdatePurchaseOrderJob::dispatchNow($purchaseOrder, $data);
            return $updatedPurchaseOrder;
        }, 1);
    }

}
