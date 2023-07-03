<?php

namespace App\Http\Requests\Teamviewer;

use App\Models\Deployment;
use App\Models\TeamviewerConnection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pipeline\Pipeline;

class FetchTeamviewerRequest extends FormRequest
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
        $deviceId = 0;

        $json = json_decode($deployment->info);
        if ($json != null && property_exists($json, 'teamviewer')) {
            $deviceId = $json['teamviewer'];
        }

        /** @var Builder $query */
        $query = app(Pipeline::class)->through(
            [
            ]
        )->send(
            TeamviewerConnection::query()
                ->where('tvc_device_id', $deviceId)
        )->thenReturn();


        if (!$this->has('orderBy'))
            $query->orderBy('id', 'desc');


        return $query->paginate(25);
    }
}
