<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateCustomerRequest extends FormRequest
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
     * @param Customer $customer
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle(Customer $customer)
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
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
                "description"
            );
            $data['updated_by_id'] = $this->user()->id;
            $customer->update($data);
            DB::commit();
            return redirect('/customers');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
