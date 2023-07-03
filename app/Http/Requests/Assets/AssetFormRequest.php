<?php


namespace App\Http\Requests\Assets;


trait AssetFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'serial_number' => 'nullable|string',
            'description' => 'nullable|string',
            'a_number' => 'nullable|string',
            'product_id' => 'required|numeric',
        ];
    }
}
