<?php

namespace App\Http\Requests\Articles;

use App\Http\QueryFilters\Article\ApplyInvoiceTypeFilter;
use App\Http\QueryFilters\Article\InvoiceArticleSearch;
use App\Http\QueryFilters\Article\InvoiceArticleStatus;
use App\Http\QueryFilters\OrderBy;
use App\Models\ArticleIdentity;
use App\Models\SaleOrderProduct;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchArticlesRequest extends FormRequest
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
                InvoiceArticleSearch::class,
//                ApplyInvoiceTypeFilter::class,
//                OrderBy::class,
//                InvoiceArticleStatus::class,
            ]
        )->send(SaleOrderProduct::query()
            ->with('purchaseOrderProduct.purchaseOrder.supplier','product','purchaseOrderProduct.documents')
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('created_at', 'desc');


        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
