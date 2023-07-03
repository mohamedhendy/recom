<?php

namespace App\Http\Requests\Inventory;

use App\Jobs\Inventory\StoreInventoryJob;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreInventoryAdjustmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
            'products' => 'required|array',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_cost' => 'required|numeric|min:0',
            'products.*.transaction' => 'required|in:increase,descrease',
            'products.*.comment' => 'nullable|string',
            'products.*.assets' => 'nullable|array',
            'products.*.assets.*.serial_number' => 'nullable|string',
        ];
    }

    /**
     * @return mixed
     */
    public function store()
    {
        return DB::transaction(function () {
            return StoreInventoryJob::dispatchNow($this->input('products'));
        });
    }
}
