<?php

namespace App\Http\Requests\Products;

use App\Models\ArticleIdentity;
use App\Models\Deployment;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchProductDeploymentsRequest extends FormRequest
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
                // ProductSearchQueryFilter::class,
                // ProductOrderByQueryFilter::class
            ]
        )->send(Deployment::whereIn('article_identity_id', ArticleIdentity::whereIn('article_id', $product->articles()->pluck('id'))->pluck('id'))
        )->thenReturn();

        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
