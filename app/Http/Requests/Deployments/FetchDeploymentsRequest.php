<?php

namespace App\Http\Requests\Deployments;

use App\Http\QueryFilters\Deployment\DeploymentOrderByQueryFilter;
use App\Http\QueryFilters\Deployment\DeploymentSearchQueryFilter;
use App\Models\Deployment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchDeploymentsRequest extends FormRequest
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
        $deployments = Deployment::query();

        if ($this->has('withSlots') && $this->boolean('withSlots')) {
            $deployments = $deployments->has('deploymentSlots');

        }

        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
                DeploymentSearchQueryFilter::class,
                DeploymentOrderByQueryFilter::class
            ]
        )->send(
            $deployments->with('asset')
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
