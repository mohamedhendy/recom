<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\FetchProductDeploymentsRequest;
use App\Http\Requests\Products\FetchProductsRequest;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Deployment\DeploymentCollection;
use App\Http\Resources\Product\ProductCollection;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchProductsRequest $request
     * @return ProductCollection
     */
    public function index(FetchProductsRequest $request): ProductCollection
    {
        return new ProductCollection($request->getData());
    }


    public function allProducts()
    {
        return Product::select('id', 'ean_number', 'name', 'default_sale_price', 'default_purchase_price')
            ->get()
            ->map(function ($item) {
                $item->full_name = $item->ean_number ? sprintf('%s - %s', $item->ean_number, $item->name) : $item->name;
                $item->display_name = $item->full_name;
                return $item;
            });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreProductRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreProductRequest $request)
    {
        return $request->handle();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param Product              $product
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        return $request->handle($product);
    }


    public function deployments(Product $product, FetchProductDeploymentsRequest $fetchProductDeploymentsRequest): DeploymentCollection
    {
        return new DeploymentCollection($fetchProductDeploymentsRequest->getData($product));
    }
}
