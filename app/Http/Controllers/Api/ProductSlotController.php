<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSlots\FetchProductSlotsRequest;
use App\Http\Requests\ProductSlots\StoreProductSlotRequest;
use App\Http\Requests\ProductSlots\UpdateProductSlotRequest;
use App\Http\Resources\ProductSlot\ProductSlotCollection;
use App\Models\Product;
use App\Models\ProductSlot;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class ProductSlotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Product                  $product
     * @param FetchProductSlotsRequest $request
     * @return ProductSlotCollection
     */
    public function index(Product $product, FetchProductSlotsRequest $request): ProductSlotCollection
    {
        return new ProductSlotCollection($request->getData($product));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Product                 $product
     * @param StoreProductSlotRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(Product $product, StoreProductSlotRequest $request)
    {
        return $request->handle($product);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductSlotRequest $request
     * @param Product                  $product
     * @param ProductSlot              $slot
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateProductSlotRequest $request, Product $product, ProductSlot $slot)
    {
        return $request->handle($product, $slot);
    }

    /**
     * @param Product     $product
     * @param ProductSlot $slot
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Product $product, ProductSlot $slot): RedirectResponse
    {
        $slot->delete();
        return back();
    }
}
