<?php

namespace App\Http\Requests\Invoices;

use App\Models\Article;
use App\Models\Document;
use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class AddDocumentsRequest extends FormRequest
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
            'articles' => 'required|array',
            'articles.*.id' => 'required|integer|exists:articles,id',
            'articles.*.documents_upload' => 'required|in:true,false,1,0',
        ];
    }

    /**
     * @param Invoice $invoice
     * @return RedirectResponse|Redirector
     */
    public function handle(Invoice $invoice)
    {
        DB::beginTransaction();

        try {

            foreach ($this->input('documents') as $key => $document) {
                if ($this->hasFile("documents.{$key}.document")) {
                    $documentRequest = "documents.{$key}.document";
                    $requestDocument = $this->file($documentRequest);
                    $documentFileName = 'DOC_' . $document['type'] . '_' . $key;
                    $storagePath = $requestDocument->store('documents');


                    foreach ($this->input('articles') as $article) {
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

                        $articleEntity = Article::findOrFail($article['id']);

                        $articleEntity->documents()->create(
                            [
                                'created_by_id' => auth()->id(),
                                'updated_by_id' => auth()->id(),
                                'document_id' => $documentEntity->id,
                                'type' => $document['type']
                            ]
                        );


                        $articleEntity->update([
                            'documents_upload' => $article['documents_upload']
                        ]);

                    }
                }

            }

            DB::commit();
            $invoice->refresh();
            return redirect('/articles');
        } catch (QueryException $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
