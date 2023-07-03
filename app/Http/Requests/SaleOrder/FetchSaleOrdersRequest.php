<?php

namespace App\Http\Requests\SaleOrder;

use App\Http\QueryFilters\SaleOrder\ExecludeStockCustomersOrders;
use App\Http\QueryFilters\SaleOrder\SaleOrderBillingStatus;
use App\Http\QueryFilters\SaleOrder\SaleOrderSearch;
use App\Http\QueryFilters\SaleOrder\SaleOrderSortBy;
use App\Models\SaleOrderProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchSaleOrdersRequest extends FormRequest
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
            'per_page' => 'numeric',
        ];
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getData(): LengthAwarePaginator
    {
        $perPage = 100;
        $page = (int)$this->input('page', 1);
        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
                SaleOrderSearch::class,
                SaleOrderBillingStatus::class,
                ExecludeStockCustomersOrders::class,
                SaleOrderSortBy::class,
            ]
        )->send(
            SaleOrderProduct::query()
            ->with([
                'saleOrder.identity',
                'product.category',
                'product.warehouseProducts',
                'purchaseOrderProduct.purchaseOrder.supplier',
                'purchaseOrderProduct.relatedSaleOrdersProducts.saleOrder.identity',
                'purchaseOrderProduct.documents',
                'purchaseOrderProduct.product.warehouseProducts',
                'documents'
            ])
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('created_at', 'desc');


        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
