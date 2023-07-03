<?php

namespace App\Http\Requests\Documents;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdoadDocumentsRequest extends FormRequest
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
            'object_type' => 'required|string',
            'object_id' => 'required|integer',
        ];
    }


    /**
     * @param $object
     * @return mixed
     */
    public function handle($object)
    {

        return DB::transaction(function () use ($object) {
            foreach ($this->input('documents') as $key => $document) {
                if ($this->hasFile("documents.{$key}.document")) {
                    $documentRequest = "documents.{$key}.document";
                    $requestDocument = $this->file($documentRequest);
                    $documentFileName = 'DOC_' . $document['type'] . '_' . $key;
                    $storagePath = $requestDocument->store('documents');
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

                    $object->documents()->create(
                        [
                            'created_by_id' => auth()->id(),
                            'updated_by_id' => auth()->id(),
                            'document_id' => $documentEntity->id,
                            'type' => $document['type']
                        ]
                    );
                }

            }


        });
    }
}
