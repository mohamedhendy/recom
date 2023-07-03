<?php

namespace App\Http\Requests\Products;

use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreProductRequest extends FormRequest
{
    use ProductFormRequest;

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
     * @return Product|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
                "name",
                "ean_number",
                "category_id",
                "comment",
                "default_sale_price",
                "default_purchase_price",
                "manufacturer",
                "manufacturer_number",
                "model",
                "default_info"
            );
            $data['created_by_id'] = $this->user()->id;
            $product = Product::create($data);
            DB::commit();

            if ($this->input('return_created')) {
                return $product;
            }

            return redirect()->back();
            
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
