<?php

namespace App\Http\Requests\DeploymentSlots;

use App\Models\Deployment;
use App\Models\DeploymentSlot;
use App\Models\ProductSlot;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreDeploymentSlotRequest extends Request
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
     * @param Deployment  $deployment
     * @param ProductSlot $productSlot
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(Deployment $deployment, ProductSlot $productSlot)
    {
        if ($deployment->asset->product_id != $productSlot->product_id) {
            throw new Exception("products do not match");
        }

        DB::beginTransaction();

        try {
            $data = array(
                "deployment_id" => $deployment->id,
                "product_slot_id" => $productSlot->id,
                "created_by_id" => Auth::user()->id,
                "info" => $productSlot->default_info,
            );

            DeploymentSlot::create($data);
            DB::commit();

            return redirect('/deployments');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
