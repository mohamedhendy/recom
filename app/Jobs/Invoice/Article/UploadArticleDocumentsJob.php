<?php

namespace App\Jobs\Invoice\Article;

use App\Models\Article;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UploadArticleDocumentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Collection
     */
    private $documents;
    private $article;
    private $articleKey;
    /**
     * @var Request
     */
    private $request;

    public function __construct($documents, Article $article, $articleKey, Request $request)
    {
        $this->documents = $documents;
        $this->article = $article;
        $this->articleKey = $articleKey;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->documents as $key => $document) {
            if ($this->request->hasFile("articles.{$this->articleKey}.documents.{$key}.document")) {
                $documentRequest = "articles.{$this->articleKey}.documents.{$key}.document";
                $requestDocument = $this->request->file($documentRequest);
                $documentFileName = 'DOC_' . $document['type'] . '_' . $this->article->id . '_' . $key;
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

                $this->article->documents()->create(
                    [
                        'document_id' => $documentEntity->id,
                        'type' => $document['type'],
                    ]
                );
            }

        }
    }

}
