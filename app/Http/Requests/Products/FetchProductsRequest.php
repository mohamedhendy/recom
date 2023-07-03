<?php

namespace App\Http\Requests\Products;

use App\Http\QueryFilters\Product\ProductOrderByQueryFilter;
use App\Http\QueryFilters\Product\ProductSearchQueryFilter;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchProductsRequest extends FormRequest
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
     * @return LengthAwarePaginator
     */
    public function getData(): LengthAwarePaginator
    {
        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
                ProductSearchQueryFilter::class,
                ProductOrderByQueryFilter::class
            ]
        )->send(
            Product::query()
                ->with([
                    'category',
                    'warehouseProducts'
                ])
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
