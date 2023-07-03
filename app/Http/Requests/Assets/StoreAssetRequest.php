<?php

namespace App\Http\Requests\Assets;


use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreAssetRequest extends FormRequest
{
    use AssetFormRequest;

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
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store()
    {
        DB::beginTransaction();

        try {
            $this->user()->createdAssets()->create($this->only(
                'serial_number',
                'description',
                'a_number',
                'product_id',
            ));

            DB::commit();
            return redirect('/assets');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
