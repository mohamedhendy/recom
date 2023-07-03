<?php


namespace App\Http\QueryFilters\SaleOrder;


use App\Http\QueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class SaleOrderSearch extends Filter
{
    /**
     * @param Builder $builder
     * @return Builder
     */
    protected function applyFilters(Builder $builder): Builder
    {
        $searchValue = request('search');


        return $builder->where('sale_order_products.id', 'ILIKE', "%{$searchValue}%")
            ->orWhere('sale_order_products.description', 'ILIKE', "%{$searchValue}%")
            ->orWhere('sale_order_products.billed_quantity', 'ILIKE', "%{$searchValue}%")
            ->orWhere('sale_order_products.created_at', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('saleOrder.identity', function (Builder $qq) use ($searchValue) {
                $searchArray = explode(' ', $searchValue);
                foreach ($searchArray as $searchKey) {
                    $qq = $qq->where('name', 'ILIKE', "%{$searchKey}%");
                }

                $qq->orWhere('number', 'ILIKE', "%{$searchValue}%");
            })
            ->orWhereHas('purchaseOrderProduct', function (Builder $qq) use ($searchValue) {
                $qq->where('id', 'ILIKE', "%{$searchValue}%")->orWhereHas('purchaseOrder', function (Builder $qqq) use ($searchValue) {
                    $qqq->where('id', 'ILIKE', "%{$searchValue}%")->orWhere('number', 'ILIKE', "%{$searchValue}%");
                })->orWhereHas('purchaseOrder.supplier', function (Builder $qqq) use ($searchValue) {
                    $searchArray = explode(' ', $searchValue);
                    foreach ($searchArray as $searchKey) {
                        $qqq = $qqq->where('name', 'ILIKE', "%{$searchKey}%");
                    }

                    $qqq->orWhere('number', 'ILIKE', "%{$searchValue}%");
                })->orWhereHas('assets', function (Builder $qqqq) use ($searchValue) {
                    $qqqq
                        ->where('sale_order_product_id', null)->where(function ($query) use ($searchValue) {
                            return $query->where('id', 'ILIKE', "%{$searchValue}%")
                                ->orWhere('serial_number', 'ILIKE', "%{$searchValue}%")
                                ->orWhere('a_number', 'ILIKE', "%{$searchValue}%")
                                ->orWhere('description', 'ILIKE', "%{$searchValue}%");

                        });
                });
            })
            ->orWhereHas('product', function (Builder $qq) use ($searchValue) {
                $searchArray = explode(' ', $searchValue);
                foreach ($searchArray as $searchKey) {
                    $qq = $qq->where('name', 'ILIKE', "%{$searchKey}%");
                }
            })
            ->orWhereHas('saleOrder', function (Builder $qq) use ($searchValue) {
                $qq->where('id', 'ILIKE', "%{$searchValue}%")->orWhere('number', 'ILIKE', "%{$searchValue}%");
            })->orWhereHas('assets', function (Builder $qqq) use ($searchValue) {
                $qqq
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
