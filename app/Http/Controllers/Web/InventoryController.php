<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Inventory/InventoryAdjustingIndexPage');
    }

    public function create(): Response
    {
        return Inertia::render(
            'Inventory/CreateInventoryAdjustingPage'
        );
    }


    public function show(Inventory $inventory): Response
    {
        return Inertia::render('Inventory/ShowInventoryPage', [
            'entity' => $inventory->load('inventoryProducts.product', 'inventoryProducts.assets')
        ]);
    }

//    public function edit(Invoice $beginningInventory): Response
//    {
//        return Inertia::render(
//            'Inventory/Update',
//            [
//                'invoice' => $beginningInventory->load('articles.documents.document', 'articles.articleIdentities.identity', 'articles.articleIdentities.project', 'articles.articleIdentities.deployments', 'articles.product'),
//                'customers' => Customer::with('projects')->orderBy('number')->get(),
//                'products' => Product::orderBy('name')->get(),
//                'categories' => Category::all(),
//                'invoice_type' => 'beginning-inventory'
//            ]
//        );
//    }
}
