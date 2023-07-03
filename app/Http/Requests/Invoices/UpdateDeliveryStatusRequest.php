<?php

namespace App\Http\Requests\Invoices;

use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\Article;
use App\Models\ArticleIdentity;
use App\Models\Invoice;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class UpdateDeliveryStatusRequest extends FormRequest
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
            'articles' => 'required|array',
            'articles.*.id' => 'required|integer|exists:articles,id',
            'articles.*.received_quantity' => 'required|integer',
            'articles.*.article_identities' => 'required|array',
            'articles.*.article_identities.*.id' => 'required|integer|exists:article_identity,id',
            'articles.*.article_identities.*.received_quantity' => 'required|integer|min:0',
            'articles.*.article_identities.*.deployments' => 'array',
            'articles.*.article_identities.*.deployments.*.fillable' => 'required|boolean',
            'articles.*.article_identities.*.deployments.*.number' => 'nullable|string',
            'articles.*.article_identities.*.deployments.*.serial' => 'nullable|string',
            'articles.*.article_identities.*.deployments.*.comment' => 'nullable|string',
        ];
    }

    /**
     * @param Invoice $invoice
     * @return RedirectResponse|Redirector
     * @throws Throwable
     */
    public function handle(Invoice $invoice)
    {
        try {
            DB::beginTransaction();

            throw_if((int)collect($this->input('articles'))->sum('received_quantity') <= 0, ValidationException::withMessages([
                'invoice' => 'should add minimum 1 received item '
            ]));
            foreach ($this->input('articles') as $key => $article) {
                $article = collect($article);
                $articleEntity = Article::findOrFail($article->get('id'));
                $this->validateArticleDeliveryStatusData($articleEntity, $key, $article);
                $this->updateArticleDeliveryStatus($articleEntity, $key, $article);
            }
            DB::commit();

            return redirect('/articles/' . $invoice->id . '/edit');
        } catch (QueryException | Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @param Article $articleEntity
     * @param         $key
     * @param         $articleCollection
     * @throws Throwable
     */
    private function validateArticleDeliveryStatusData(Article $articleEntity, $key, $articleCollection)
    {
        $deliveredAmount = (int)$this->getArticleTotalDeliveredQuantity($articleEntity, $articleCollection);
        $receivedAmount = $articleCollection->get('received_quantity');
        throw_if($deliveredAmount > $articleEntity->quantity, ValidationException::withMessages([
            "articles.{$key}.received_quantity" => 'invalid delivered amount'
        ]));
        $articleCustomers = $this->input("articles.{$key}.article_identities");
        foreach ($articleCustomers as $customerKey => $customerInvoice) {
            $customerInvoice = collect($customerInvoice);
            $customerInvoiceEntity = ArticleIdentity::findOrFail($customerInvoice->get('id'));
            $this->validateArticleCustomerDeliveryStatus($customerInvoiceEntity, $customerInvoice, $key, $customerKey);
        }


        $stockAndArticleDeliveredAmount = (int)collect($articleCustomers)->sum('received_quantity');
        throw_if((int)$stockAndArticleDeliveredAmount !== (int)$receivedAmount, ValidationException::withMessages([
            "articles.{$key}.received_quantity" => 'invalid delivered amount not equal ' . $deliveredAmount
        ]));
    }

    /**
     * @param $articleEntity
     * @param $articleCollection
     * @return int
     */
    private function getArticleTotalDeliveredQuantity($articleEntity, $articleCollection): int
    {
        return (int)$articleCollection->get('received_quantity') + (int)$articleEntity->delivered_quantity;
    }

    /**
     * @param $customerInvoiceEntity
     * @param $customerInvoice
     * @param $key
     * @param $customerKey
     * @throws Throwable
     */
    private function validateArticleCustomerDeliveryStatus($customerInvoiceEntity, $customerInvoice, $key, $customerKey)
    {
        $customerDeliveredAmount = (int)$customerInvoice->get('received_quantity');
        $totalDeliveredAmount = $customerDeliveredAmount + (int)$customerInvoiceEntity->delivered_quantity;
        throw_if((int)$totalDeliveredAmount > $customerInvoiceEntity->quantity, ValidationException::withMessages([
            "articles.{$key}.article_identities.{$customerKey}.received_quantity" => 'invalid delivered amount'
        ]));

    }

    /**
     * @param Article $articleEntity
     * @param         $key
     * @param         $articleCollection
     */
    private function updateArticleDeliveryStatus(Article $articleEntity, $key, $articleCollection)
    {
        $articleCustomers = $this->input("articles.{$key}.article_identities");
        foreach ($articleCustomers as $customerKey => $customerInvoice) {
            $customerInvoice = collect($customerInvoice);
            $customerInvoiceEntity = ArticleIdentity::findOrFail($customerInvoice->get('id'));
            $this->updateArticleCustomerDeliveryStatus($customerInvoiceEntity, $customerInvoice, $key, $customerKey);
        }
    }

    /**
     * @param ArticleIdentity $articleCustomer
     * @param                 $customerInvoice
     * @param                 $key
     * @param                 $customerKey
     */
    private function updateArticleCustomerDeliveryStatus(ArticleIdentity $articleCustomer, $customerInvoice, $key, $customerKey)
    {
        $customerDeliveredAmount = (int)$customerInvoice->get('received_quantity');
        $totalDeliveredAmount = $customerDeliveredAmount + (int)$articleCustomer->delivered_quantity;
        $articleCustomer->update([
            'delivered_quantity' => $totalDeliveredAmount
        ]);


        $deployments = $this->input("articles.{$key}.article_identities.{$customerKey}.deployments");
        foreach ($deployments as $productIndex => $product) {
            $product = collect($product);
            $actionIndex = $productIndex + 1;
            if ($actionIndex <= $customerDeliveredAmount && $product['fillable']) {
                $articleCustomer->deployments()->create([
                    'supplier_id' => $articleCustomer->article->invoice->supplier_id,
                    'a_number' => $product['number'],
                    'type' => "",
                    'name' => "",
                    'description' => $product['comment'],
                    'serial_number' => $product['serial'],
                    'created_by_id' => auth()->id(),
                ]);

            }
        }

        $article = $articleCustomer->article;
        CreateProductWareHouseTransactionJob::dispatchNow(null, $article->product, $article, $customerDeliveredAmount, 'debit', auth()->user());

    }
}
