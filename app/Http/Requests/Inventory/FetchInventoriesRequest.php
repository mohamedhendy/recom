<?php

namespace App\Http\Requests\Inventory;

use App\Models\InventoryProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchInventoriesRequest extends FormRequest
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

    public function getData(): LengthAwarePaginator
    {
        $perPage = 100;
        $page = (int)$this->input('page', 1);
        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
//                PurchaseOrderSearch::class,
//                PurchaseOrderDeliveryStatus::class,
//                PurchaseOrderSortBy::class,
            ]
        )->send(
            InventoryProduct::query()
                ->with([
                    'inventory',
                    'product',
                    'assets'
                ])
        )->thenReturn();


//        if (!$this->has('orderBy'))
        $query->orderBy('created_at', 'desc');


        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
