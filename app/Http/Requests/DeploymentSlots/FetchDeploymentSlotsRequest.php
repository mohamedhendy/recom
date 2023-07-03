<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Http\QueryFilters\Deployment\DeploymentOrderByQueryFilter;
use App\Http\QueryFilters\Deployment\DeploymentSearchQueryFilter;
use App\Http\QueryFilters\DeploymentSlot\DeploymentSlotOrderByQueryFilter;
use App\Http\QueryFilters\DeploymentSlot\DeploymentSlotSearchQueryFilter;
use App\Models\Deployment;
use App\Models\DeploymentSlot;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchDeploymentSlotsRequest extends FormRequest
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
     * @param Deployment $deployment
     * @return LengthAwarePaginator
     */
    public function getData(Deployment $deployment): LengthAwarePaginator
    {
        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
                DeploymentSlotSearchQueryFilter::class,
                DeploymentSlotOrderByQueryFilter::class
            ]
        )->send(
            DeploymentSlot::query()
                ->where('deployment_id', $deployment->id)
                ->with('productSlot')
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
