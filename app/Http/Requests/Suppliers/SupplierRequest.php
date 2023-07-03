<?php


namespace App\Http\Requests\Suppliers;


trait SupplierRequest
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
            "description" => 'nullable|string',
            'tax_id' => 'nullable',
            "vat_id" => 'nullable',
            "address_salutation" => 'nullable|string',
            "address_name" => 'nullable|string',
            "address_street" => 'nullable|string',
            "address_zip_code" => 'nullable|numeric',
            "address_city" => 'nullable|string',
            "address_country" => 'nullable|string',
            "contact_details1_phone1" => 'nullable|string',
            "contact_details1_mobile" => 'nullable|string',
            "contact_details1_email" => 'nullable|string',
            "bank_name" => 'nullable|string',
            "bank_bic" => 'nullable|string',
            "bank_iban" => 'nullable|string',
        ];
    }
}
