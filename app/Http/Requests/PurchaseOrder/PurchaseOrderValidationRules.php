<?php


namespace App\Http\Requests\PurchaseOrder;


trait PurchaseOrderValidationRules
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => 'required|integer|exists:suppliers,id', /// match = id
            'internal_id' => 'nullable|string', /// match = id
            'due_date' => "required|date",
            'issue_date' => "required|date",
            'products' => 'required|array',
            'products.*.id' => 'nullable|integer|exists:purchase_order_products,id',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.related_sale_orders_products' => 'nullable|array',
            'products.*.related_sale_orders_products.*.id' => 'nullable|integer|exists:sale_order_products,id',
            'products.*.related_sale_orders_products.*.identity_id' => 'required|integer',
            'products.*.related_sale_orders_products.*.identity_type' => 'required|in:customer,staff',
            'products.*.related_sale_orders_products.*.quantity' => 'required|numeric|min:1',
            'products.*.related_sale_orders_products.*.price' => 'required|numeric|min:0',

            'products.*.assets' => 'nullable|array',
            'products.*.assets.*.id' => 'nullable|integer|exists:assets,id',
        ];
    }
}
