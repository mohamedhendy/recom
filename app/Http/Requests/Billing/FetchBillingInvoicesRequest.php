<?php

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;

class FetchBillingInvoicesRequest extends FormRequest
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
            //
            'page' => 'integer',
            'type' => 'nullable|in:INVOICE,RECURRING,CREDIT,OFFER,REMINDER,DUNNING,STORNO,STORNO_CREDIT,DELIVERY,PDF,CHARGE,CHARGE_CONFIRM,LETTER,ORDER',
            'is_draft' => 'nullable|boolean'
        ];
    }

    public function getUrl($path = 'customers', $queryParam = ""): string
    {
        $page = 1;
        if ($this->has('page') && $this->filled('page'))
            $page = $this->input('page');

        return "{$path}?page={$page}&&limit=500&&{$queryParam}";
    }


    public function detectDocumentType(): string
    {
        if ($this->has('type') && $this->filled('type'))
            return $this->input('type');
        return 'INVOICE';
    }

    public function detectIsDraft(): int
    {
        if ($this->has('is_draft') && $this->filled('is_draft'))
            return (bool)$this->input('is_draft') ? 1 : 0;
        return 1;
    }
}
