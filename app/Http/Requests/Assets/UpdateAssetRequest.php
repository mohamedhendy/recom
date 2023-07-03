<?php

namespace App\Http\Requests\Assets;

use App\Models\Asset;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateAssetRequest extends FormRequest
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
     * @param Asset $asset
     * @return Asset
     * @throws ValidationException
     */
    public function update(Asset $asset)
    {
        DB::beginTransaction();

        try {

            $data = $this->only(
                'serial_number',
                'description',
                'a_number',
                'product_id',
            );
            $data['updated_by_id'] = $this->user()->id;

            $asset->update($data);
            DB::commit();
            return $asset->fresh();
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
