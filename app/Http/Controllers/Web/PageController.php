<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PageController extends Controller
{
    /**
     * @param Document $document
     * @return void|StreamedResponse
     * @throws NotFoundHttpException
     * @throws HttpException
     */
    public function downloadFile(Document $document): StreamedResponse
    {
        if (Storage::disk('local')->exists($document->path))
            return Storage::disk('local')->download($document->path,$document->original_name);

        abort(404);
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function redirectToArticles()
    {
        return redirect('/sale_purchase_orders');
    }
}
