<?php

namespace App\Jobs\EasyBill;

use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\ArticleIdentity;
use App\Models\Deployment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MarkDeploymentsAsBilledJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $articleIdentitiesList,$salesArticleIdentity;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleIdentitiesList = [],$salesArticleIdentity = null)
    {
        //$
        $this->articleIdentitiesList = $articleIdentitiesList;
        $this->salesArticleIdentity = $salesArticleIdentity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ((array)$this->articleIdentitiesList as $index => $item) {
            $customerInvoiceEntity = ArticleIdentity::find($item['id']);
            $billedAmount = (int)count($item['deployments']);
            $customerInvoiceEntity->update([
                'billed_quantity' => $customerInvoiceEntity->billed_quantity + (int)count($item['deployments'])
            ]);

            foreach ($item['deployments'] as $deployment) {
                $cDeployment = collect($deployment);
                $deploymentEntity = Deployment::find((int) $cDeployment->get('id'));
                if(!$deploymentEntity)
                {
                    $deploymentEntity = Deployment::where('a_number',$cDeployment->get('a_number'))->orWhere('serial_number',$cDeployment->get('serial_number'))->orderBy('id','desc')->firstOrFail();
                }

                $updateData  = [
                    'billed' => true,
                    'billed_by_id' => auth()->id(),
                    'billed_at' => now()
                ];

                if($this->salesArticleIdentity)
                {
                    $updateData['sales_article_identity_id'] = $this->salesArticleIdentity->id;
                }
                $deploymentEntity->update( $updateData );

            }

            $article = $customerInvoiceEntity->article;
            CreateProductWareHouseTransactionJob::dispatchNow(null, $article->product, $article, $billedAmount, 'credit', auth()->user());
        }
    }
}
