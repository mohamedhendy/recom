<?php


namespace App\Http\Requests\Products;


trait ProductFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => 'required|string',
            "ean_number" => 'nullable|string',
            "comment" => 'nullable|string',
            "category_id" => 'nullable|integer|exists:categories,id',
            "default_purchase_price" => "required|numeric",
            "default_sale_price" => "required|numeric",
            "manufacturer" => "nullable|string",
            "model" => "nullable|string",
            "default_info" => "nullable|string"
        ];

    }
}
