<?php

namespace App\Jobs\Invoice\Article;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

class ValidateArticleAmountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article;
    /**
     * @var int
     */
    private $index;

    /**
     * Create a new job instance.
     *
     * @param     $article
     * @param int $index
     */
    public function __construct($article, $index = 0)
    {
        //
        $this->article = collect($article);
        $this->index = $index;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ValidationException
     */
    public function handle()
    {
        $totalAmount = (int)$this->article->get('quantity', 0);
        $stockAndCustomersAndStaffsAmount = (int)collect(request("articles.{$this->index}.article_identities"))->sum('quantity');

        if ($stockAndCustomersAndStaffsAmount != $totalAmount) {
            throw  ValidationException::withMessages(
                [
                    "articles.{$this->index}.quantity" => ["Quantity Amount Doesn't match stock and clients amounts"]
                ]
            );

        }
    }
}
