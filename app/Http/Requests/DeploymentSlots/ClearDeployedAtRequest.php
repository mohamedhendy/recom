<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Models\Deployment;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClearDeployedAtRequest extends Request
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
     * @param Deployment $deployment
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle(Deployment $deployment)
    {
        DB::beginTransaction();

        try {
            $deployment->update(['deployed_slot_id' => null]);
            DB::commit();
            return back();
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
