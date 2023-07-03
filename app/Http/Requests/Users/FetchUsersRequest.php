<?php

namespace App\Http\Requests\Users;

use App\Http\QueryFilters\User\UserOrderByQueryFilter;
use App\Http\QueryFilters\User\UserSearchQueryFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchUsersRequest extends FormRequest
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
                UserSearchQueryFilter::class,
                UserOrderByQueryFilter::class
            ]
        )->send(User::query())->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
