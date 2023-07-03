<?php

namespace App\Jobs\Invoice\Article;

use App\Jobs\EasyBill\HandleEasyBillJob;
use App\Jobs\EasyBill\MarkDeploymentsAsBilledJob;
use App\Jobs\Inventory\Beginning\CreateDeploymentsJob;
use App\Models\Article;
use App\Models\Customer;
use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection as IlluminateCollection;
use Illuminate\Support\Facades\Session;

class CreateArticleIdentityInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $article;
    /**
     * @var IlluminateCollection
     */
    private $invoiceType;
    private $identity;
    private $articleIndex;

    /**
     * Create a new job instance.
     *
     * @param Article $article
     * @param         $identity
     * @param string  $invoiceType
     */
    public function __construct(Article $article, $identity, $invoiceType = 'purchase',$articleIndex = null)
    {
        $this->article = $article;
        $this->invoiceType = $invoiceType;
        $this->identity = $identity;
        $this->articleIndex = $articleIndex;
    }





    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if (Arr::get($this->identity, 'type') === 'customer') {
            $identityEntity = Customer::findOrFail(Arr::get($this->identity, 'identity_id'));
        } else {
            $identityEntity = Staff::findOrFail(Arr::get($this->identity, 'identity_id'));
        }

        $requestProjectId = Arr::get($this->identity, 'project_id');
        $projectId = is_numeric($requestProjectId) && $requestProjectId > 0 ? Arr::get($this->identity, 'project_id') : null;
        $articleIdentity = $identityEntity->articleIdentities()->create(
            [
                'article_id' => $this->article->id,
                'quantity' => $this->identity['quantity'],
                'sales_price' => Arr::get($this->identity, 'sales_price', ""),
                'type' => $this->invoiceType,
                'project_id' => $projectId,
                'invoice_id' => $this->article->invoice_id,
                'supplier_id' => $this->article->invoice->supplier_id,
                'description' => Arr::get($this->identity, 'description', ""),
                'created_by_id' => auth()->id(),
            ]
        );

        if ($this->invoiceType == 'beginning-inventory') {
            CreateDeploymentsJob::dispatchNow($articleIdentity, Arr::get($this->identity, "deployments"));
        }

        if ($this->invoiceType == 'sale') {
            $articleIdentityData =  $articleIdentity->toArray();
            $articleIdentityData['deployments'] = Arr::get($this->identity, "deployments");
    

            Session::put('invalid_available_quantity_error_key',"articles.{$this->articleIndex}.available_quantity");


            HandleEasyBillJob::dispatchNow(true, [$articleIdentityData], request('draft_invoice_id'), false);
            MarkDeploymentsAsBilledJob::dispatchNow([$articleIdentityData],$articleIdentity);

        }
    }
}
