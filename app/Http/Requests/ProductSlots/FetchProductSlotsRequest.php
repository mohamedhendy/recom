<?php

namespace App\Http\Requests\ProductSlots;

use App\Http\QueryFilters\ProductSlot\ProductSlotOrderByQueryFilter;
use App\Http\QueryFilters\ProductSlot\ProductSlotSearchQueryFilter;
use App\Models\Product;
use App\Models\ProductSlot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchProductSlotsRequest extends FormRequest
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
        ];
    }

    /**
     * @param Product $product
     * @return LengthAwarePaginator
     */
    public function getData(Product $product): LengthAwarePaginator
    {
        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
                ProductSlotSearchQueryFilter::class,
                ProductSlotOrderByQueryFilter::class
            ]
        )->send(ProductSlot::query()
            ->where([
                'product_id' => $product->id
            ]))
            ->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('number', 'asc');


        return $query->paginate(25);
    }
}
