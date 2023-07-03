<?php

use App\Http\Controllers\Web\ArticleController;
use App\Http\Controllers\Web\AssetController;
use App\Http\Controllers\Web\CategoryController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\DeploymentController;
use App\Http\Controllers\Web\DocumentController;
use App\Http\Controllers\Web\InventoryController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\ProductSlotController;
use App\Http\Controllers\Web\PurchaseOrderController;
use App\Http\Controllers\Web\SaleOrderController;
use App\Http\Controllers\Web\SalePurchaseOrderController;
use App\Http\Controllers\Web\SupplierController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\WarehouseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
});
Route::get('language/{language}', function ($language) {
    Session()->put('locale', $language);

    return redirect()->back();
})->name('language');

Route::middleware(['auth:sanctum', 'verified', 'inertia.admin'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'redirectToArticles']);
    Route::get('/files/{document}/download', [PageController::class, 'downloadFile'])->name('files.download');
    Route::resource('inventories', InventoryController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class)->only("create", "index", "show", "edit");
    Route::get('updateFromEasyBill', [CustomerController::class, 'easyBillUpdate'])->name('customersupdate');
    Route::resource('products', ProductController::class);
    Route::prefix('products/{product}')->name('products.')->group(function () {
        Route::get('deployments', [ProductController::class, 'deployments'])->name('deployments');
        Route::resource('slots', ProductSlotController::class)->only('create', 'show', 'edit');
    });
    Route::resource('suppliers', SupplierController::class);
    Route::resource('users', UserController::class)->only("create", "index", "show", "edit");
    Route::resource('warehouses', WarehouseController::class)->only("create", "index", "show", "edit");
    Route::resource('sale_orders', SaleOrderController::class)->only("create", "index", "show", "edit");
    Route::prefix('sale_orders')->name('sale_orders.')->group(function () {
        Route::get('/{saleOrderProduct}/update_billing', [SaleOrderController::class, 'updateBilling'])->name('update_billing');
    });

    Route::prefix('documents/')->name('documents.')->group(function () {
        Route::get('{purchaseOrderProduct}/update_purchase_order_product', [DocumentController::class, 'updatePurchaseOrderProduct'])->name('update_purchase_order_product');
        Route::get('{saleOrderProduct}/update_sale_order_product', [DocumentController::class, 'updateSaleOrderProduct'])->name('update_sale_order_product');
    });
    Route::resource('deployments', DeploymentController::class);
    Route::prefix('deployments/{deployment}')->name('deployments.')->group(function () {
        Route::get('insertAtSlot/{deploymentSlot}', [DeploymentController::class, 'insertAtSlot']);
        Route::prefix('connectSlot/{deploymentSlot}')->name('connectSlot.')->group(function () {
            Route::get('', [DeploymentController::class, 'connectSlotChooseDeployment']);
            Route::get('{targetDeployment}', [DeploymentController::class, 'connectSlotChooseSlot']);
        });
    });
    Route::resource('assets', AssetController::class);

    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::resource('sale_purchase_orders', SalePurchaseOrderController::class)->only('index');
    Route::prefix('purchase_orders')->name('purchase_orders.')->group(function () {
        Route::get('/{purchaseOrderProduct}/{saleOrderProduct}/update_delivery_status', [PurchaseOrderController::class, 'updateDeliveryStatus'])->name('update_delivery_status');
        Route::get('/{purchaseOrder}/documents', [PurchaseOrderController::class,'documents'])->name('documents');
    });


    Route::group(['prefix' => 'articles'], function () {
        Route::get('', [ArticleController::class, 'articleIndex'])->name('index');
        Route::get('/create', [ArticleController::class, 'createPurchaseOrder'])->name('create');
        Route::get('/{articleIdentity}/update_delivery_status', [ArticleController::class, 'updateInvoiceDeliveryStatus'])->name('update_delivery_status');
        Route::get('/{purchaseOrder}', [ArticleController::class, 'showPurchaseOrder'])->name('show_purchase_order');
        Route::get('/{invoice}/edit', [ArticleController::class, 'updateInvoicePage'])->name('update');
        Route::get('/{invoice}/update_documents', [ArticleController::class, 'updateDocuments'])->name('update_documents');
        Route::get('/{invoice}/qrcodes', [ArticleController::class, 'qrcodes'])->name('qrcodes');
        Route::get('/{invoiceArticle}/{customerInvoiceId}/billing', [ArticleController::class, 'billingArticle'])->name('billing');
        Route::get('/billing', function () {
            return Inertia\Inertia::render('Articles/Billing');
        })->name('show.billing');

        Route::get('/add-documents', function () {
            return Inertia\Inertia::render('Articles/AddDocuments');
        })->name('add-documents');

        Route::get('/review-documents', function () {
            return Inertia\Inertia::render('Articles/ReviewDocuments');
        })->name('review-documents');
    });
});
