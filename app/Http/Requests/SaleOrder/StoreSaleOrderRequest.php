<?php

namespace App\Http\Requests\SaleOrder;

use App\Jobs\SaleOrder\StoreSaleOrderJob;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreSaleOrderRequest extends FormRequest
{
    use SaleOrderValidationRules;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return DB::transaction(function () {
            $data = $this->only('internal_id', 'due_date', 'issue_date');
            $identity = $this->input('identity_type') == 'staff' ? Staff::findOrFail($this->input('identity_id')) : Customer::findOrFail($this->input('identity_id'));
            return StoreSaleOrderJob::dispatchNow($identity, $this->input('products'), $data);
        }, 1);
    }
}
