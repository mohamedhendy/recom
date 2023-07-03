<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Deployments\FetchDeploymentsRequest;
use App\Http\Requests\Deployments\StoreDeploymentRequest;
use App\Http\Requests\Deployments\UpdateDeploymentRequest;
use App\Http\Resources\Deployment\DeploymentCollection;
use App\Models\Deployment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class DeploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchDeploymentsRequest $request
     * @return DeploymentCollection
     */
    public function index(FetchDeploymentsRequest $request): DeploymentCollection
    {
        return new DeploymentCollection($request->getData());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDeploymentRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreDeploymentRequest $request)
    {
        return $request->handle();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDeploymentRequest $request
     * @param Deployment              $deployment
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateDeploymentRequest $request, Deployment $deployment)
    {
        return $request->handle($deployment);
    }
}
