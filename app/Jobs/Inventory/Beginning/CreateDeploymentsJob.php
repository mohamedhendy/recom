<?php

namespace App\Jobs\Inventory\Beginning;

use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\ArticleIdentity;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateDeploymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $articleIdentity, $deployments;

    /**
     * Create a new job instance.
     *
     * @param ArticleIdentity $articleIdentity
     * @param array           $deployments
     */
    public function __construct(ArticleIdentity $articleIdentity, $deployments = [])
    {
        $this->articleIdentity = $articleIdentity;
        $this->deployments = $deployments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //
        $this->articleIdentity->update([
            'delivered_quantity' => $this->articleIdentity->quantity
        ]);

        foreach ($this->deployments as $key => $value) {
            $deployment = collect($value);
            $this->articleIdentity->deployments()->create([
                'a_number' => $deployment['a_number'],
                'type' => "",
                'name' => "",
                'description' => $deployment['description'],
                'serial_number' => $deployment['serial_number'],
                'created_by_id' => auth()->id(),
            ]);
        }


        $remming = $this->articleIdentity->quantity - count($this->deployments);
        for ($i = 0; $i < $remming; $i++) {
            $this->articleIdentity->deployments()->create([
                'a_number' => "",
                'type' => "",
                'name' => "",
                'description' => "",
                'serial_number' => "",
                'created_by_id' => auth()->id(),
            ]);
        }

        $article = $this->articleIdentity->article;
        CreateProductWareHouseTransactionJob::dispatchNow(null, $article->product, $article, $this->articleIdentity->quantity, 'debit', auth()->user());
    }
}
