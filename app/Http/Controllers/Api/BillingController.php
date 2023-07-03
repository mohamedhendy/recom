<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\FetchBillingInvoicesRequest;
use App\Http\Requests\Billing\StoreDeploymentBillingRequest;
use App\Http\Resources\EasyBill\EasyBillInvoiceCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class BillingController extends Controller
{
    /**
     * @param FetchBillingInvoicesRequest $request
     * @return EasyBillInvoiceCollection
     */
    public function index(FetchBillingInvoicesRequest $request): EasyBillInvoiceCollection
    {
        $getDetectedType = $request->detectDocumentType();
        $isDraft = $request->detectIsDraft();
        $url = $request->getUrl('documents', "is_draft={$isDraft}&&type={$getDetectedType}");
        $result = app('EasyBill')->request('GET', $url);
        return new EasyBillInvoiceCollection($result);
    }

    /**
     * @param StoreDeploymentBillingRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreDeploymentBillingRequest $request)
    {
        return $request->store();
    }
}
