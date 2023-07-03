<?php

namespace App\Http\Requests\Deployments;

use App\Models\Customer;
use App\Models\Deployment;
use App\Models\Staff;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreDeploymentRequest extends FormRequest
{
    use DeploymentFormRequest;

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
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
                "name",
                "o_number",
                "address",
                "building",
                "room",
                "exact_position",
                "contact",
                "asset_id",
                // "deployed_slot_id",
                "type",
                "info",
            );
            $data['created_by_id'] = $this->user()->id;

            $identity = $this->input('identity_type') == 'staff' ? Staff::findOrFail($this->input('identity_id')) : Customer::findOrFail($this->input('identity_id'));

            $identity->deployments()->create($data);
            DB::commit();

            return redirect('/deployments');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
