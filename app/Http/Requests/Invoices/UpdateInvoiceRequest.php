<?php

namespace App\Http\Requests\Invoices;

use App\Jobs\Invoice\Article\CreateArticleIdentityInvoiceJob;
use App\Jobs\Invoice\Article\ValidateArticleAmountJob;
use App\Models\Article;
use App\Models\Customer;
use App\Models\Deployment;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Staff;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateInvoiceRequest extends FormRequest
{
    use InvoiceRequest;

    public function rules(): array
    {
        $invoiceRules = $this->invoiceRules();

        $additionalRules = [];

        if ($this->getInvoiceType() == 'invoice') {
            $additionalRules = [
                'issue_date' => 'required|date', /// match = id
                'due_date' => 'required|date', /// match = id
            ];
        }
        return array_merge($invoiceRules, $additionalRules);
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @param Invoice $invoice
     * @return RedirectResponse|Redirector
     */
    public function update(Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $this->updateInvoice($invoice);
            $articles = collect($this->input('articles'));
            $this->deleteArticles($invoice, $articles);
            $this->updateArticles($invoice, $articles);
            DB::commit();
            if ($this->getInvoiceType() === 'beginning-inventory')
                return redirect('beginning-inventories');


            return redirect('/articles');
        } catch (QueryException $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    /**
     * @param $invoice
     */
    public function updateInvoice($invoice)
    {
        $data = $this->only("due_date", "issue_date");
        $data['internal_id'] = $this->has('internal_id') && $this->filled('internal_id') ? $this->input('internal_id') : $invoice->id;
        $invoice->update($data);
    }

    /**
     * @param $invoice
     * @param $articles
     */
    public function deleteArticles($invoice, $articles)
    {
        $articlesEntities = $invoice->articles()->get();
        $requestArticlesEntities = $articles->pluck('id')->toArray();
        foreach ($articlesEntities as $articleEntry) {

            if (!in_array($articleEntry->id, $requestArticlesEntities)) {
                $articleEntry->documents()->delete();
                $articleEntry->articleIdentities()->delete();
                $articleEntry->delete();
            }
        }
    }

    /**
     * @param $invoice
     * @param $articles
     */
    public function updateArticles($invoice, $articles)
    {
        foreach ($articles as $key => $article) {
            $id = Arr::get($article, "id");

            $articleEntry = null;

            if (is_int((int)$id) && $id != "undefined")
                $articleEntry = Article::find($id);


            if ($articleEntry) {
                $this->updateArticle($articleEntry, $article, $key);
            } else {
                $this->createArticle($invoice, $key, $article);
            }
        }
    }

    /**
     * @param $articleEntry
     * @param $article
     * @param $key
     */
    public function updateArticle($articleEntry, $article, $key)
    {
        ValidateArticleAmountJob::dispatchNow($article, $key);
        $product = Product::find($article['product_id']);
        $articleEntry->update(
            [
                'product_id' => $article['product_id'],
                'description' => Arr::get($article, 'description', ''),
                'quantity' => $article['quantity'],
                'name' => $product->name,
                // 'category_id' => $article['category_id'],
                // 'subcategory_id' => $article['subcategory_id'],
                'cost_price' => $article['cost_price'],
                'currency_code' => $article['currency_code'],
                'updated_by_id' => $this->user()->id,
            ]
        );

        $this->updateArticleIdentities($articleEntry, $key);
        $this->updateArticleDocuments($articleEntry, $key);
    }

    /**
     * @param $articleEntry
     * @param $key
     */
    private function updateArticleIdentities($articleEntry, $key)
    {
        $articleIdentitiesInput = collect($this->input("articles.{$key}.article_identities"));


        $existingIdentities = $articleEntry->articleIdentities()->get();

        $existingIdentitiesRequest = $articleIdentitiesInput->pluck('id')->toArray();


        foreach ($existingIdentities as $existingIdentity) {
            if (!in_array($existingIdentity->id, $existingIdentitiesRequest)) {

                $existingIdentity->delete();
            }
        }

        foreach ($articleIdentitiesInput as $articleIdentity) {


            if (Arr::get($articleIdentity, 'type') == 'customer') {
                $identityEntity = Customer::findOrFail(Arr::get($articleIdentity, 'identity_id'));
            } else {
                $identityEntity = Staff::findOrFail(Arr::get($articleIdentity, 'identity_id'));
            }

            $identityArticleEntity = $articleEntry->articleIdentities()->where('id', Arr::get($articleIdentity, 'id'))->first();


            if ($identityArticleEntity) {
                $identityArticleEntity->update(
                    [
                        'identity_id' => $identityEntity->id,
                        'quantity' => $articleIdentity['quantity'],
                        'sales_price' => Arr::get($articleIdentity, 'sales_price', ""),
                        'description' => Arr::get($articleIdentity, 'description', ""),
                        'updated_by_id' => auth()->id(),
                    ]
                );


                if (Arr::get($articleIdentity, 'deployments')) {

                    $this->updateDeployments(Arr::get($articleIdentity, 'deployments'));
                }
            } else {
                CreateArticleIdentityInvoiceJob::dispatchNow($articleEntry, $articleIdentity);
            }
        }
    }

    /**
     * @param $deployments
     */
    private function updateDeployments($deployments)
    {

        foreach ($deployments as $deployment) {

            $deployemntEntitty = Deployment::find($deployment['id']);

            if ($deployemntEntitty) {
                $deployemntEntitty->update([
                    'a_number' => Arr::get($deployment, 'a_number') ?? "",
                    'description' => Arr::get($deployment, 'description') ?? "",
                    'serial_number' => Arr::get($deployment, 'serial_number') ?? "",
                ]);
            }
        }
    }

    /**
     * @param $articleEntry
     * @param $key
     */
    private function updateArticleDocuments($articleEntry, $key)
    {
        $documentsInput = collect($this->input("articles.{$key}.documents"));

        $existing = $articleEntry->documents()->get();
        $existingRequest = $documentsInput->pluck('id')->toArray();
        foreach ($existing as $exists) {

            if (!in_array($exists->id, $existingRequest)) {
                if (Storage::exists($exists->path)) {
                    Storage::delete($exists->path);
                }
                $exists->delete();
            }
        }

        if ($this->has("articles.{$key}.documents"))
            $this->createArticleDocuments($articleEntry, $key);
    }
}
