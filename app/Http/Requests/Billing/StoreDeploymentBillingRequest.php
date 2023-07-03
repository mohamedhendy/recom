<?php

namespace App\Http\Requests\Billing;

use App\Jobs\EasyBill\CreateNewEasyBillDraftJob;
use App\Jobs\EasyBill\HandleEasyBillJob;
use App\Jobs\EasyBill\MarkDeploymentsAsBilledJob;
use App\Jobs\EasyBill\PushDeploymentsToEasyBillInvoiceJob;
use App\Jobs\EasyBill\ReturnDraftInvoiceItemsJob;
use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\Article;
use App\Models\ArticleIdentity;
use App\Models\Deployment;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class StoreDeploymentBillingRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'create_new_draft_invoice' => 'boolean',
            'draft_invoice_id' => 'nullable|integer', //required_if:create_new_draft_invoice,false

            'mark_as_billed' => 'nullable',
            'customers' => 'required|array',
            'customers.*.id' => 'required|integer|exists:article_identity,id',
            'customers.*.article_id' => 'required|integer|exists:articles,id',
            'customers.*.deployments' => 'array',
            'customers.*.deployments.*.id' => 'required|integer|exists:deployments,id',
            'customers.*.deployments.*.a_number' => 'nullable|string',
            'customers.*.deployments.*.serial_number' => 'nullable|string',
            'customers.*.deployments.*.description' => 'nullable|string',
        ];
    }

    /**
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store()
    {
        try {
           HandleEasyBillJob::dispatchNow($this->has('mask_as_billed') && $this->filled('mask_as_billed'),$this->input('customers'),$this->input('draft_invoice_id'),$this->input('create_new_draft_invoice', false));
           MarkDeploymentsAsBilledJob::dispatchNow($this->input('customers'));

           return redirect('/articles');
        } catch (ValidationException | Exception $exception) {
            throw $exception;
        }
    }

}
