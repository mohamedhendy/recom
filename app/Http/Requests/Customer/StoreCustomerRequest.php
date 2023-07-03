<?php

namespace App\Http\Requests\Customer;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreCustomerRequest extends FormRequest
{
    use CustomerFormRequest;

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
    public function handle()
    {
        DB::beginTransaction();

        try {
            $this->user()->createdCustomers()->create($this->only(
                'number',
                "name",
                "type",
                "address_salutation",
                "address_name",
                "address_street",
                "address_zip_code",
                "address_city",
                "address_country",
                "contact_phone1",
                "contact_mobile",
                "contact_email",
                "note"
            ));

            DB::commit();
            return redirect('/customers');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
