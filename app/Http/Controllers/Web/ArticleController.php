<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleIdentity;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SaleOrderProduct;
use App\Models\Staff;
use App\Models\Supplier;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function articleIndex(): Response
    {
        return Inertia::render('Articles/Index', [
            'not_billed' => SaleOrderProduct::where([['purchase_order_product_id','!=',null]])->whereRaw('quantity > billed_quantity')->count(),
            'not_received' => PurchaseOrderProduct::whereRaw('quantity > delivered_quantity')->count(),
        ]);
    }

    public function qrcodes(Invoice $invoice): Response
    {
        return Inertia::render('Articles/Qrcodes', [
            'invoice' => $invoice->load('articles.articleIdentities.deployments', "articles.product")
        ]);
    }

    public function createPurchaseOrder(): Response
    {
        return Inertia::render(
            'Articles/Create', [
                'products' => Product::orderBy('name')->get(),
                'customers' => Customer::with('projects')->orderBy('number')->get(),
                'staffs' => Staff::all(),
                'suppliers' => Supplier::all(),
                'categories' => Category::where('parent_category_id')->with('subCategories')->get(),
                'invoice_type' => 'invoice'
            ]
        );
    }

    public function updateInvoicePage(PurchaseOrder $purchaseOrder): Response
    {

//        return Inertia::render(
//            'Articles/Update', [
//                'invoice' => $invoice->load('articles.documents.document', 'articles.articleIdentities.identity', 'articles.articleIdentities.project', 'articles.articleIdentities.deployments', 'articles.product'),
//                'customers' => Customer::with('projects')->orderBy('number')->get(),
//                'staffs' => Staff::all(),
//                'products' => Product::orderBy('name')->get(),
//                'suppliers' => Supplier::all(),
//                'categories' => Category::all(),
//                'invoice_type' => 'invoice'
//            ]
//        );
    }

    public function showPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        return $purchaseOrder;
//        return Inertia::render(
//            'Articles/Show', [
//                'invoice' => $invoice->load('articles.documents.document', 'articles.articleIdentities.identity', 'articles.articleIdentities.project', "articles.articleIdentities.deployments", 'articles.product'),
//                'customers' => Customer::with('projects')->orderBy('number')->get(),
//                'staffs' => Staff::all(),
//                'products' => Product::orderBy('name')->get(),
//                'suppliers' => Supplier::all(),
//                'categories' => Category::all()
//            ]
//        );
    }

    public function updateInvoiceDeliveryStatus(ArticleIdentity $articleIdentity): Response
    {
        return Inertia::render(
            'Articles/UpdateDeliveryStatus', [
                'invoice' => $articleIdentity->article->invoice->load('articles.documents.document', 'articles.articleIdentities.identity', 'articles.articleIdentities.deployments', 'articles.product'),
                'articleIdentity' => $articleIdentity
            ]
        );
    }

    public function billingArticle(Article $invoiceArticle, $customerInvoiceId): Response
    {
        return Inertia::render(
            'Articles/Billing', [
                'article' => $invoiceArticle,
                'identities' => $invoiceArticle->articleIdentities()->where([['id', $customerInvoiceId]])
                    ->whereRaw("delivered_quantity > billed_quantity")->with(['identity', 'deployments' => function ($deployment) {
                        return $deployment->where('billed', false);
                    }])
                    ->get()

            ]
        );
    }

    public function updateDocuments(Invoice $invoice): Response
    {
        return Inertia::render(
            'Articles/AddDocuments', [
            'invoice' => $invoice->load('articles.documents.document')
        ]);
    }
}
