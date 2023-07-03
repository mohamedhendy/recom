<?php

namespace App\Http\Requests\Assets;

use App\Http\QueryFilters\Asset\AssetOrderByQueryFilter;
use App\Http\QueryFilters\Asset\AssetSearchQueryFilter;
use App\Models\Asset;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchAssetsRequest extends FormRequest
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
                AssetSearchQueryFilter::class,
                AssetOrderByQueryFilter::class
            ]
        )->send(
            Asset::query()
            ->with('product')
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
