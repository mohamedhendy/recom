<?php

namespace App\Http\Requests\ProductSlots;

use App\Models\Product;
use App\Models\ProductSlot;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateProductSlotRequest extends FormRequest
{
    use ProductSlotFormRequest;

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
     * @param Product     $product
     * @param ProductSlot $productSlot
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle(Product $product, ProductSlot $productSlot)
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
                "name",
                "number",
                "default_info"
            );

            $data['updated_by_id'] = $this->user()->id;

            $productSlot->update($data);

            DB::commit();

            return redirect()->route('products.edit', $product);

        } catch (QueryException | ValidationException $exception) {

            DB::rollBack();

            throw $exception;
        }
    }
}
