<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Models\Deployment;
use App\Models\DeploymentSlot;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InsertDeploymentAtRequest extends Request
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
     * @param Deployment     $parentDeployment
     * @param DeploymentSlot $parentDeploymentSlot
     * @param Deployment     $targetDeployment
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Deployment $parentDeployment, DeploymentSlot $parentDeploymentSlot, Deployment $targetDeployment)
    {
        if ($parentDeploymentSlot->deployment_id != $parentDeployment->id) {
            throw new Exception("deployments not matching");
        }

        if ($targetDeployment->deployed_slot_id != null) {
            throw new Exception("already deployed");
        }

        DB::beginTransaction();

        try {
            $data = [
                'deployed_slot_id' => $parentDeploymentSlot->id,
                'updated_by_id' => Auth::user()->id,
            ];

            $targetDeployment->update($data);
            DB::commit();
            return redirect('/products');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
