<?php


namespace App\Http\Requests\Categories;


trait CategoryFormRequest
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
            "parent_category_id" => 'required|numeric',
        ];

    }
}
