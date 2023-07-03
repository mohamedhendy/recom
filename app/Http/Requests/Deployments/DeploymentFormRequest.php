<?php


namespace App\Http\Requests\Deployments;


trait DeploymentFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => 'nullable|string',
            "o_number" => 'nullable|string',
            "address" => 'nullable|string',
            "building" => 'nullable|string',
            "room" => 'nullable|string',
            "exact_position" => 'nullable|string',
            "contact" => 'nullable|string',
            "asset_id" => 'required|numeric',
            "identity_id" => 'required|numeric',
            "identity_type" => 'required|string|in:customer,staff',
            // "deployed_slot_id" => 'nullable|string',
            "type" => 'nullable|string',
            "info" => 'nullable|string',
        ];
    }
}
