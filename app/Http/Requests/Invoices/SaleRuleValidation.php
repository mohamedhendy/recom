<?php


namespace App\Http\Requests\Invoices;

use App\Jobs\Invoice\Article\CreateArticleIdentityInvoiceJob;
use App\Jobs\Invoice\Article\UploadArticleDocumentsJob;
use App\Jobs\Invoice\Article\ValidateArticleAmountJob;
use App\Models\Article;
use App\Models\Product;

trait SaleRuleValidation
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function invoiceRules(): array
    {
        return [
            'internal_id' => 'nullable|string', /// match = id
            'draft_invoice_id' => 'nullable|string', /// match = id
            'type' => "nullable|string|in:invoice,begining-inventory",
            'articles' => 'required|array',
            'articles.*.product_id' => 'required|integer|exists:products,id',
            'articles.*.quantity' => 'required|integer',
            'articles.*.sales_price' => 'required|numeric',
            'articles.*.currency_code' => 'required|string',
            'articles.*.article_identities' => 'required|array',
            'articles.*.article_identities.*.id' => 'nullable|integer|exists:article_identity,id',
            'articles.*.article_identities.*.identity_id' => 'required|integer',
            'articles.*.article_identities.*.type' => 'required|string|in:customer,staff',
            'articles.*.article_identities.*.quantity' => 'required|integer',
            // 'articles.*.article_identities.*.sales_price' => 'required|numeric',
            'articles.*.article_identities.*.description' => 'nullable',
            'articles.*.article_identities.*.deployments' => 'nullable|array',
            'articles.*.article_identities.*.deployments.*.a_number' => 'nullable|string|exists:deployments,a_number',
            'articles.*.article_identities.*.deployments.*.serial_number' => 'nullable|string|exists:deployments,serial_number',
            'articles.*.article_identities.*.deployments.*.description' => 'nullable|string',
            'articles.*.documents.*.id' => 'nullable|integer|exists:has_documents,id',
            'articles.*.documents' => 'array',
            'articles.*.documents.*' => 'required',
            'articles.*.documents.*.document' => 'required',
            'articles.*.documents.*.type' => 'required|string' //|in:invoice,delivery_note,cancellation,order_confirmation,deliver_avis,rma,other
        ];
    }


    protected function createArticle($invoice, $key, $article)
    {

        ValidateArticleAmountJob::dispatchNow($article, $key);
        $articleCollection = collect($article);
        $product = Product::find($article['product_id']);
        $dbArticle = $invoice->articles()->create(
            [
                'product_id' => $article['product_id'],
                "name" => $product->name,
                'quantity' => $article['quantity'],
                'cost_price' => $product->unit_cost_average,
                'sales_price' => $articleCollection->get('sales_price'),
                'description' => $articleCollection->get('description'),
                'currency_code' => $articleCollection->get('currency_code'),
                'created_by_id' => $this->user()->id,
                'type' => $this->getInvoiceType()
            ]
        );
        if ($this->has("articles.{$key}.article_identities")) {
            foreach ($this->input("articles.{$key}.article_identities") as $identity) {
                CreateArticleIdentityInvoiceJob::dispatchNow($dbArticle, $identity, $this->getInvoiceType(), $key);
            }
        }
        return $dbArticle;
    }


    public function getInvoiceType(): string
    {
        $invoiceType = "";
        if ($this->has('invoice_type') && $this->filled('invoice_type')) {
            $invoiceType = $this->input('invoice_type');
        }
        if (!in_array($invoiceType, ['sale'])) {
            $invoiceType = "sale";
        }


        return $invoiceType;
    }
}
