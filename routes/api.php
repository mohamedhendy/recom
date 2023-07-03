<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DeploymentController;
use App\Http\Controllers\Api\DeploymentSlotController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductSlotController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\SaleOrderController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\TeamViewerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WarehouseTransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:sanctum', 'inertia.admin'])->name('api.')->group(function () {

    Route::delete('documents/{document}', [DocumentController::class, 'delete'])->name('documents.delete');
    Route::post('documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');

    Route::resource('articles', ArticleController::class);
    Route::resource('billing', BillingController::class);
    Route::resource('categories', CategoryController::class);
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('list/all', [CategoryController::class, 'allCategories'])->name('all');
    });
    Route::resource('products', ProductController::class);

    Route::prefix('/products')->name('products.')->group(function () {
        Route::get('list/all', [ProductController::class, 'allProducts'])->name('all');
    });

    Route::prefix('products/{product}')->name('products.')->group(function () {
        Route::get('deployments', [ProductController::class, 'deployments'])->name('deployments');
        Route::resource('slots', ProductSlotController::class);
    });

    Route::resource('suppliers', SupplierController::class);

    Route::prefix('/suppliers')->name('suppliers.')->group(function () {
        Route::get('list/all', [SupplierController::class, 'allSuppliers'])->name('all');
    });

    Route::resource('users', UserController::class);
    Route::resource('warehouse_transactions', WarehouseTransactionController::class);
    Route::get('inventories/pdf', [InventoryController::class, 'createPDF'])->name('inventoryPdf');
    Route::resource('inventories', InventoryController::class);
    Route::resource('sale_orders', SaleOrderController::class);

    Route::prefix('sale_orders')->name('sale_orders.')->group(function () {
        Route::post('/{saleOrderProduct}/update_billing', [SaleOrderController::class, 'updateBilling'])->name('update_billing');
    });

    Route::resource('deployments', DeploymentController::class);
    Route::prefix('/deployments/{deployment}')->name('deployments.')->group(function () {
        Route::delete('clearDeployedAt', [DeploymentSlotController::class, 'clearDeployedAt']);
        Route::resource('tv', TeamviewerController::class)->only('index');
        Route::prefix('/slots')->name('slots.')->group(function () {
            Route::get('', [DeploymentSlotController::class, 'index']);
            Route::post('/add/{productSlot}', [DeploymentSlotController::class, 'store']);
            Route::prefix('/{deploymentSlot}')->name('deploymentSlots.')->group(function () {
                Route::post('', [DeploymentSlotController::class, 'store']);

                Route::delete('', [DeploymentSlotController::class, 'destroy']);

                Route::post('setParent/{targetDeployment}', [DeploymentSlotController::class, 'insertAtSlot']);

                Route::post('connect/{targetDeployment}/{targetDeploymentSlot}', [DeploymentSlotController::class, 'connect']);

                Route::delete('disconnect/{deploymentSlotConnection}', [DeploymentSlotController::class, 'disconnect']);
            });
        });
    });

    Route::resource('assets', AssetController::class);
    Route::prefix('/assets')->name('assets.')->group(function () {
        Route::get('list/all', [AssetController::class, 'allAssets'])->name('all');
        Route::post('update/all', [AssetController::class, 'updateAll'])->name('update_all');
    });
    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::prefix('purchase_orders')->name('purchase_orders.')->group(function () {
        Route::post('/{purchaseOrderProduct}/update_delivery_status', [PurchaseOrderController::class, 'updateDeliveryStatus'])->name('update_delivery_status');
        Route::post('/{purchaseOrder}/documents', [PurchaseOrderController::class, 'documents'])->name('documents');
    });

    Route::resource('customers', CustomerController::class);
    Route::post('customers/{customer}/updateSingle', [CustomerController::class, 'updateSingle']);
    Route::prefix('/customers')->name('customers.')->group(function () {
        Route::get('list/all', [CustomerController::class, 'allCustomers'])->name('all');
    });
    Route::prefix('/staffs')->name('staffs.')->group(function () {
        Route::get('list/all', [StaffController::class, 'allStaffs'])->name('all');
    });

    Route::resource('purchases', PurchaseController::class);

    Route::prefix('/purchases')->name('purchases.')->group(function () {
        Route::match(['PATCH', 'PUT', 'POST'], '{invoice}/update-delivery-status', [PurchaseController::class, 'updateDeliveryStatus']);
        Route::post('{purchase}/add-documents', [PurchaseController::class, 'addDocuments']);
        Route::post('/{purchase}', [PurchaseController::class, 'update']);
        Route::delete('/{purchase}', [PurchaseController::class, 'destory']);
    });
});
