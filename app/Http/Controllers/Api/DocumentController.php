<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\UpdoadDocumentsRequest;
use App\Models\HasDocument;
use Exception;
use Illuminate\Http\RedirectResponse;

class DocumentController extends Controller
{
    /**
     * @param HasDocument $document
     * @return RedirectResponse
     * @throws Exception
     */
    public function delete(HasDocument $document): RedirectResponse
    {
        $document->delete();
        return back();
    }

    /**
     * @param UpdoadDocumentsRequest $updoadDocumentsRequest
     * @return mixed
     */
    public function upload(UpdoadDocumentsRequest $updoadDocumentsRequest)
    {
        $object = app($updoadDocumentsRequest->input('object_type'))->findOrFail($updoadDocumentsRequest->input('object_id'));
        return $updoadDocumentsRequest->handle($object);
    }
}
