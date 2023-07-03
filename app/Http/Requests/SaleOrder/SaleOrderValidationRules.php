<?php


namespace App\Http\Requests\SaleOrder;


trait SaleOrderValidationRules
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identity_id' => 'required|integer|exists:suppliers,id', /// match = id
            'identity_type' => 'nullable|string', /// match = id
            'due_date' => "required|date",
            'issue_date' => "required|date",
            'products' => 'required|array',
            'products.*.id' => 'nullable|integer|exists:purchase_order_products,id',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0'
        ];
    }
}
