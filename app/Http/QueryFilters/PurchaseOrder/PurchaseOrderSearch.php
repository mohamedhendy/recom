<?php


namespace App\Http\QueryFilters\PurchaseOrder;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PurchaseOrderSearch extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');


        return $builder->where('purchase_order_products.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('purchase_order_products.description', 'ILIKE', "%{$searchValue}%")
            ->orWhere('purchase_order_products.delivered_quantity', 'ILIKE', "%{$searchValue}%")
            ->orWhere('purchase_order_products.created_at', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('purchaseOrder.supplier', function (Builder $qq) use ($searchValue) {
                $searchArray = explode(' ', $searchValue);
                foreach ($searchArray as $searchKey) {
                    $qq = $qq->where('name', 'ILIKE', "%{$searchKey}%");
                }

                $qq->orWhere('number', 'ILIKE', "%{$searchValue}%");
            })
            ->orWhereHas('product', function (Builder $qq) use ($searchValue) {
                $searchArray = explode(' ', $searchValue);
                foreach ($searchArray as $searchKey) {
                    $qq = $qq->where('name', 'ILIKE', "%{$searchKey}%");
                }

            })
            ->orWhereHas('purchaseOrder', function (Builder $qq) use ($searchValue) {
                $qq->where('id', 'ILIKE', "%{$searchValue}%")
                    ->orWhere('number', 'ILIKE', "%{$searchValue}%");
            })->orWhereHas('assets', function (Builder $qqqq) use ($searchValue) {
                $qqqq
                    ->where('id', 'ILIKE', "%{$searchValue}%")
                    ->orWhere('serial_number', 'ILIKE', "%{$searchValue}%")
                    ->orWhere('a_number', 'ILIKE', "%{$searchValue}%")
                    ->orWhere('description', 'ILIKE', "%{$searchValue}%");

            });
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
