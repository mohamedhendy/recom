<?php

namespace App\Http\Requests\Customer;

use App\Models\Customer;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateCustomerRequestSingle extends FormRequest
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

    public function rules()
    {
        return [];
    }

    /**
     * @param Customer $customer
     * @return bool
     * @throws ValidationException
     * @throws Exception
     */
    public function handleSingle(Customer $customer): bool
    {
        if (!$this->filled('field')) {
            throw new Exception('field missing');
        }

        $field = $this->input("field");

        if (!array_key_exists($field, $customer->getAttributes())) {
            throw new Exception('field to replace does not exist');
        }

        $validated = $this->validate([
            "data" => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $data = [
                $field => $validated["data"]
            ];

            $data['updated_by_id'] = $this->user()->id;
            $customer->update($data);
            DB::commit();

        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }

        return true;
    }
}
