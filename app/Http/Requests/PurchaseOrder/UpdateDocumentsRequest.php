<?php

namespace App\Http\Requests\PurchaseOrder;

use App\Models\Document;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class UpdateDocumentsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; //$this->user()
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
            'documents' => 'required|array',
            'documents.*.type' => 'required|string|in:invoice,delivery_note,cancellation,order_confirmation,deliver_avis,rma,other',
            'documents.*.document' => 'required|file',
            'purchase_order_products' => 'required|array',
            'purchase_order_products.*.id' => 'required|integer|exists:purchase_order_products,id',
            'purchase_order_products.*.documents_upload' => 'required|in:true,false,1,0',
        ];
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     * @return RedirectResponse|Redirector
     */
    public function handle(PurchaseOrder $purchaseOrder)
    {
        return DB::transaction(function () use ($purchaseOrder) {
            foreach ($this->input('documents') as $key => $document) {
                if ($this->hasFile("documents.{$key}.document")) {
                    $documentRequest = "documents.{$key}.document";
                    $requestDocument = $this->file($documentRequest);
                    $documentFileName = 'DOC_' . $document['type'] . '_' . $key;
                    $storagePath = $requestDocument->store('documents');


                    foreach ($this->input('purchase_order_products') as $purchaseOrderProduct) {
                        $documentEntity = (new Document())->create(
                            [
                                'name' => $documentFileName,
                                'original_name' => $requestDocument->getClientOriginalName(),
                                'mime_type' => $requestDocument->getMimeType(),
                                'size' => $requestDocument->getSize(),
                                'created_by_id' => auth()->id(),
                                'updated_by_id' => auth()->id(),
                                'path' => $storagePath,
                            ]
                        );

                        $articleEntity = PurchaseOrderProduct::findOrFail($purchaseOrderProduct['id']);

                        $articleEntity->documents()->create(
                            [
                                'created_by_id' => auth()->id(),
                                'updated_by_id' => auth()->id(),
                                'document_id' => $documentEntity->id,
                                'type' => $document['type']
                            ]
                        );


                        $articleEntity->update([
                            'documents_upload' => $purchaseOrderProduct['documents_upload']
                        ]);

                    }
                }
            }

            return $purchaseOrder;
        });


    }
}
