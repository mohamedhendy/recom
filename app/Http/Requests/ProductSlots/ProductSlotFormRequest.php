<?php


namespace App\Http\Requests\ProductSlots;


trait ProductSlotFormRequest
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
            "number" => 'required|numeric',
            "default_info" => 'nullable|string',
        ];

    }
}
