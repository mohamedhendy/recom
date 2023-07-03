<?php


namespace App\Http\Requests\Users;


trait UserFormRequest
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
            "email" => 'required|string',
            'role' => 'required|in:user,admin'
        ];

    }
}
