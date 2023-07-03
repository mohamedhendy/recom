<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\AddDocumentsRequest;
use App\Http\Requests\Invoices\StorePurchaseRequest;
use App\Http\Requests\Invoices\UpdateDeliveryStatusRequest;
use App\Http\Requests\Invoices\UpdateInvoiceRequest;
use App\Models\Article;
use App\Models\Document;
use App\Models\Invoice;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;
use function storage_path;

class PurchaseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StorePurchaseRequest $request
     * @return RedirectResponse|Redirector
     * @throws Throwable
     */
    public function store(StorePurchaseRequest $request)
    {
        return $request->store();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvoiceRequest $request
     * @param Invoice              $purchase
     * @return RedirectResponse|Redirector
     */
    public function update(UpdateInvoiceRequest $request, Invoice $purchase)
    {
        return $request->update($purchase);
    }

    /**
     * Update Delivery status of the Invoice.
     *
     * @param UpdateDeliveryStatusRequest $request
     * @param Invoice                     $purchase
     * @return RedirectResponse|Redirector
     * @throws Throwable
     */
    public function updateDeliveryStatus(UpdateDeliveryStatusRequest $request, Invoice $purchase)
    {
        return $request->handle($purchase);
    }

    /**
     * Attach Documents to Invoice record.
     *
     * @param AddDocumentsRequest $request
     * @param Invoice             $purchase
     * @return RedirectResponse|Redirector
     */
    public function addDocuments(AddDocumentsRequest $request, Invoice $purchase)
    {
        return $request->handle($purchase);
    }

    /**
     * @param Request $request
     * @param Invoice $purchase
     * @return BinaryFileResponse
     */
    public function viewDocument(Request $request, Invoice $purchase): BinaryFileResponse
    {
        $documentId = $request->query('document_id');
        /* @var Document $document */
        $document = $purchase->documents()->where('id', $documentId)->first();

        return response()->file(
            storage_path('app/' . $document->path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $document->path . '"'
            ]
        );
    }

    /**
     * @param Invoice $purchase
     * @return Application|RedirectResponse|Redirector
     * @throws Throwable
     */
    public function destory(Invoice $purchase)
    {
        DB::beginTransaction();

        try {
            $purchase->articles()->each(function (Article $article) {
                $article->articleIdentities()->each(function ($articleIdentity) {
                    $articleIdentity->deployments()->delete();
                    $articleIdentity->delete();
                });
                $article->delete();
            });

            $purchase->delete();
            DB::commit();
            return redirect('/articles');
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }

    }
}
