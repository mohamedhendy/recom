<?php


namespace App\Http\Requests\Customer;


trait CustomerFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'number' => 'required|numeric',
            "name" => 'required|string',
            "note" => 'nullable|string',
            'type' => 'required|in:client,stock',
            "address_salutation" => 'nullable|string',
            "address_name" => 'nullable|string',
            "address_street" => 'nullable|string',
            "address_zip_code" => 'nullable|numeric',
            "address_city" => 'nullable|string',
            "address_country" => 'nullable|string',
            "contact_phone1" => 'nullable|string',
            "contact_mobile" => 'nullable|string',
            "contact_email" => 'nullable|string',

        ];

    }
}
