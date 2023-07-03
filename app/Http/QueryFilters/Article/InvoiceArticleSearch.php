<?php


namespace App\Http\QueryFilters\Article;


use App\Http\QueryFilters\Filter;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder;

class InvoiceArticleSearch extends Filter
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
            ->orWhere('sale_order_products.delivered_quantity', 'ILIKE', "%{$searchValue}%")
            ->orWhere('sale_order_products.billed_quantity', 'ILIKE', "%{$searchValue}%")
            ->orWhere('sale_order_products.created_at', 'ILIKE', "%{$searchValue}%")
            ->orWhereHas('saleOrder.identity', function (Builder $qq) use ($searchValue) {


                if ($qq->getModel() instanceof Customer) {

                    $qq->where('name', 'ILIKE', "%{$searchValue}%")->orWhere('number', 'ILIKE', "%{$searchValue}%");
                } else {
                    $qq->where('name', 'ILIKE', "%{$searchValue}%")->orWhere('staff_number', 'ILIKE', "%{$searchValue}%");
                }
            })
            ->orWhereHas('purchaseOrderProduct', function ($qq) use ($searchValue) {
//                $searchArray = explode(' ', $searchValue);
//                foreach ($searchArray as $searchKey) {
//////                    $qq = $qq->where('name', 'ILIKE', "%{$searchKey}%");
////                }
//                orW
                $qq->where('description', 'ILIKE', "%{$searchValue}%")->orWhereHas('purchaseOrder', function ($qqq) use ($searchValue) {
                    $qqq->where('id', 'ILIKE', "%{$searchValue}%")->orWhere('number', 'ILIKE', "%{$searchValue}%")->orWhereHas('supplier', function ($qqqq) use ($searchValue) {

                        $searchArray = explode(' ', $searchValue);
                        foreach ($searchArray as $searchKey) {
                            $qqqq = $qqqq->where('name', 'ILIKE', "%{$searchKey}%");
                        }

                        $qqqq->orWhere('number', 'ILIKE', "%{$searchValue}%");
                    });
                })->orWhereHas('product', function ($qqqq) use ($searchValue) {
                    $searchArray = explode(' ', $searchValue);
                    foreach ($searchArray as $searchKey) {
                        $qqqq = $qqqq->where('name', 'ILIKE', "%{$searchKey}%");
                    }
                });
            });
//            ->orWhereHas('deployments', function ($qqq) use ($searchValue) {
//                $qqq->where('a_number', 'ILIKE', "%{$searchValue}%")->orWhere('serial_number', 'ILIKE', "%{$searchValue}%")->orWhere('description', 'ILIKE', "%{$searchValue}%")
//                    ->orWhere('mac_address', 'ILIKE', "%{$searchValue}%")
//                    ->orWhere('license_key', 'ILIKE', "%{$searchValue}%")
//                    ->orWhere('storage_location', 'ILIKE', "%{$searchValue}%")
//                    ->orWhere('o_number', 'ILIKE', "%{$searchValue}%")
//                    ->orWhere('name', 'ILIKE', "%{$searchValue}%");
//            });
    }

    /**
     * @return string
     */
    protected function filterName(): string
    {
        return 'search';
    }
}
