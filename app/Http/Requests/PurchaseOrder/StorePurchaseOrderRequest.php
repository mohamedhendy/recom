<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Jobs\PurchaseOrder\StorePurchaseOrderJob;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StorePurchaseOrderRequest extends FormRequest
{
    use PurchaseOrderValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }


    public function store()
    {
        return DB::transaction(function () {
            $data = $this->only('internal_id', 'products', 'supplier_id', 'due_date', 'issue_date', 'invoice_year');
            return StorePurchaseOrderJob::dispatchNow($data);
        }, 1);
    }

}
