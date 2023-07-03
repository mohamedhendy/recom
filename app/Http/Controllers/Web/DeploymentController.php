<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Deployment;
use App\Models\DeploymentSlot;
use Inertia\Inertia;
use Inertia\Response;

class DeploymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Deployments/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("Deployments/Form");
    }


    /**
     * Display the specified resource.
     *
     * @param Deployment $deployment
     * @return Response
     */
    public function show(Deployment $deployment): Response
    {
        $deployment->load([
            'deployedSlot.deployment.asset.product',
            'deployedSlot.productSlot',
            'asset.product.productSlots',
            'deploymentSlots.childDeployments.asset.product',
            'deploymentSlots.localConnections.deployment.asset.product',
            'deploymentSlots.localConnections.productSlot',
            'deploymentSlots.remoteConnections.deployment.asset.product',
            'deploymentSlots.remoteConnections.productSlot',
        ]);

        return Inertia::render("Deployments/Form", [
            'view_only' => true,
            'entity' => $deployment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Deployment $deployment
     * @return Response
     */
    public function edit(Deployment $deployment): Response
    {
        $deployment->load([
            'deployedSlot.deployment.asset.product',
            'deployedSlot.productSlot',
            'asset.product.productSlots',
            'deploymentSlots.childDeployments.asset.product',
            'deploymentSlots.localConnections.deployment.asset.product',
            'deploymentSlots.localConnections.productSlot',
            'deploymentSlots.remoteConnections.deployment.asset.product',
            'deploymentSlots.remoteConnections.productSlot',
        ]);

        return Inertia::render("Deployments/Form", [
            'entity' => $deployment
        ]);
    }

    public function insertAtSlot(Deployment $deployment, DeploymentSlot $deploymentSlot): Response
    {
        return Inertia::render("Deployments/ChooseDeployment", [
            'parentDeployment' => $deployment,
            'parentDeploymentSlot' => $deploymentSlot,
            'target' => 'deployment'
        ]);
    }

    public function connectSlotChooseDeployment(Deployment $deployment, DeploymentSlot $deploymentSlot): Response
    {
        return Inertia::render("Deployments/ChooseDeployment", [
            'parentDeployment' => $deployment,
            'parentDeploymentSlot' => $deploymentSlot,
            'target' => 'slot'
        ]);
    }

    public function connectSlotChooseSlot(Deployment $deployment, DeploymentSlot $deploymentSlot, Deployment $targetDeployment): Response
    {
        return Inertia::render("Deployments/ChooseSlot", [
            'parentDeployment' => $deployment,
            'parentDeploymentSlot' => $deploymentSlot,
            'targetDeployment' => $targetDeployment
        ]);
    }
}
