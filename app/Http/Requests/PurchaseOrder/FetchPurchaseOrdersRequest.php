<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Http\QueryFilters\PurchaseOrder\PurchaseOrderDeliveryStatus;
use App\Http\QueryFilters\PurchaseOrder\PurchaseOrderSearch;
use App\Http\QueryFilters\PurchaseOrder\PurchaseOrderSortBy;
use App\Models\PurchaseOrderProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchPurchaseOrdersRequest extends FormRequest
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
                PurchaseOrderSearch::class,
                PurchaseOrderDeliveryStatus::class,
                PurchaseOrderSortBy::class,
            ]
        )->send(
            PurchaseOrderProduct::query()
                ->with([
                    'documents',
                    'purchaseOrder.supplier',
                    'relatedSaleOrdersProducts.saleOrder.identity',
                    'relatedSaleOrdersProducts.product.warehouseProducts',
                    'product.warehouseProducts'
                ])
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('created_at', 'desc');


        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
