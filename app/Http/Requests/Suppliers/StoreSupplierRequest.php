<?php

namespace App\Http\Requests\Suppliers;


use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreSupplierRequest extends FormRequest
{
    use SupplierRequest;

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
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store()
    {
        DB::beginTransaction();

        try {
            $this->user()->createdSuppliers()->create($this->only(
                'number',
                "name",
                'tax_id',
                "vat_id",
                "address_salutation",
                "address_name",
                "address_street",
                "address_zip_code",
                "address_city",
                "address_country",
                "contact_details1_phone1",
                "contact_details1_mobile",
                "contact_details1_email",
                "bank_name",
                "bank_bic",
                "bank_iban",
                "description"
            ));

            DB::commit();
            return redirect('/suppliers');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
