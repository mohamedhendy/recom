<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeploymentSlots\ClearDeployedAtRequest;
use App\Http\Requests\DeploymentSlots\ConnectDeploymentRequest;
use App\Http\Requests\DeploymentSlots\DisconnectDeploymentRequest;
use App\Http\Requests\DeploymentSlots\FetchDeploymentSlotsRequest;
use App\Http\Requests\DeploymentSlots\InsertDeploymentAtRequest;
use App\Http\Requests\DeploymentSlots\StoreDeploymentSlotRequest;
use App\Http\Resources\DeploymentSlot\DeploymentSlotCollection;
use App\Models\Deployment;
use App\Models\DeploymentSlot;
use App\Models\DeploymentSlotConnection;
use App\Models\ProductSlot;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class DeploymentSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchDeploymentSlotsRequest $request
     * @param Deployment                  $deployment
     * @return DeploymentSlotCollection
     */
    public function index(FetchDeploymentSlotsRequest $request, Deployment $deployment): DeploymentSlotCollection
    {
        return new DeploymentSlotCollection($request->getData($deployment));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDeploymentSlotRequest $request
     * @param Deployment                 $deployment
     * @param ProductSlot                $productSlot
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreDeploymentSlotRequest $request, Deployment $deployment, ProductSlot $productSlot)
    {
        return $request->handle($deployment, $productSlot);
    }

    /**
     * @param Deployment     $deployment
     * @param DeploymentSlot $deploymentSlot
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Deployment $deployment, DeploymentSlot $deploymentSlot): RedirectResponse
    {
        $deploymentSlot->delete();

        return redirect()->route('deployments.edit', $deployment);
    }

    /**
     * @param InsertDeploymentAtRequest $request
     * @param Deployment                $deployment
     * @param DeploymentSlot            $deploymentSlot
     * @param Deployment                $targetDeployment
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function insertAtSlot(InsertDeploymentAtRequest $request, Deployment $deployment, DeploymentSlot $deploymentSlot, Deployment $targetDeployment)
    {
        return $request->handle($deployment, $deploymentSlot, $targetDeployment);
    }

    /***
     * @param ConnectDeploymentRequest $request
     * @param Deployment               $deployment
     * @param DeploymentSlot           $deploymentSlot
     * @param Deployment               $targetDeployment
     * @param DeploymentSlot           $targetDeploymentSlot
     * @return mixed
     * @throws ValidationException
     */
    public function connect(ConnectDeploymentRequest $request, Deployment $deployment, DeploymentSlot $deploymentSlot, Deployment $targetDeployment, DeploymentSlot $targetDeploymentSlot)
    {
        return $request->handle($deployment, $deploymentSlot, $targetDeployment, $targetDeploymentSlot);
    }

    /***
     * @param DisconnectDeploymentRequest $request
     * @param Deployment                  $deployment
     * @param DeploymentSlot              $deploymentSlot
     * @param DeploymentSlotConnection    $deploymentSlotConnection
     * @return RedirectResponse
     * @throws Exception
     */
    public function disconnect(DisconnectDeploymentRequest $request, Deployment $deployment, DeploymentSlot $deploymentSlot, DeploymentSlotConnection $deploymentSlotConnection): RedirectResponse
    {
        return $request->handle($deployment, $deploymentSlot, $deploymentSlotConnection);
    }

    /***
     * @param ClearDeployedAtRequest $request
     * @param Deployment             $deployment
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function clearDeployedAt(ClearDeployedAtRequest $request, Deployment $deployment): RedirectResponse
    {
        return $request->handle($deployment);
    }
}
