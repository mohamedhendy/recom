<?php

namespace App\Jobs\Invoice\Article;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddInvoiceArticleActivityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $invoiceArticle;
    private $activity;
    /**
     * @var string
     */
    private $payload;

    /**
     * Create a new job instance.
     *
     * @param InvoiceArticle  $invoiceArticle
     * @param                 $activity
     * @param string          $payload
     */
    public function __construct(InvoiceArticle $invoiceArticle, $activity, $payload = "")
    {
        //
        $this->invoiceArticle = $invoiceArticle;
        $this->activity = $activity;
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->invoiceArticle->activities()->create([

            'activity' => $this->activity,
            'payload' => $this->payload,
            'created_by_id' => auth()->id()
        ]);
    }
}
