<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Actions\Fortify\ResetUserPassword;
use App\Models\Deployment;
use App\Models\DeploymentSlotConnection;
use App\Models\DeploymentSlot;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ConnectDeploymentRequest extends Request
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
    public function handle(Deployment $parentDeployment, DeploymentSlot $parentDeploymentSlot, Deployment $targetDeployment, DeploymentSlot $targetDeploymentSlot)
    {
        if ($parentDeploymentSlot->deployment_id != $parentDeployment->id) {
            throw new Exception("parent deployments not matching");
        }

        if ($targetDeploymentSlot->deployment_id != $targetDeployment->id) {
            throw new Exception("target deployments not matching");
        }


        DB::beginTransaction();

        try {
            $data = [
                'first_deployment_slot_id' => $parentDeploymentSlot->id,
                'second_deployment_slot_id' => $targetDeploymentSlot->id,
                'created_by_id' => Auth::user()->id,
            ];

            DeploymentSlotConnection::create($data);

            DB::commit();

            return redirect()->route('deployments.edit', $parentDeployment);

        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
