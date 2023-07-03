<?php

namespace App\Http\Requests\WarehouseTransaction;

use App\Http\QueryFilters\WarehouseTransaction\WarehouseProductFilter;
use App\Http\QueryFilters\WarehouseTransaction\WarehouseProductSearchFilter;
use App\Models\WarehouseTransaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchWarehouseTransactionsRequest extends FormRequest
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
                WarehouseProductSearchFilter::class,
                WarehouseProductFilter::class
            ]
        )->send(
            WarehouseTransaction::query()
                ->with([
                    'warehouseProduct.product.category',
                    'warehouseProduct.product',
                    'warehouseProduct.warehouse',
                ])
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(50);
    }
}
