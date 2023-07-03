<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Models\Deployment;
use App\Models\DeploymentSlotConnection;
use App\Models\DeploymentSlot;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class DisconnectDeploymentRequest extends Request
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
     * @param Deployment               $deployment
     * @param DeploymentSlot           $deploymentSlot
     * @param DeploymentSlotConnection $deploymentSlotConnection
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function handle(Deployment $deployment, DeploymentSlot $deploymentSlot, DeploymentSlotConnection $deploymentSlotConnection)
    {
        if ($deploymentSlot->deployment_id != $deployment->id) {
            throw new Exception("deployments not matching");
        }

        if ($deploymentSlotConnection->first_deployment_slot_id != $deploymentSlot->id
            && $deploymentSlotConnection->second_deployment_slot_id != $deploymentSlot->id) {
            throw new Exception("slots not matching");
        }

        $deploymentSlotConnection->delete();

        return redirect()->route('deployments.edit', $deployment);
    }
}
