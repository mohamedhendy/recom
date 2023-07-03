<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSlot;
use Inertia\Inertia;
use Inertia\Response;

class ProductSlotController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return Response
     */
    public function create(Product $product): Response
    {
        return Inertia::render("ProductSlots/Form", [
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Product     $product
     * @param ProductSlot $slot
     * @return Response
     */
    public function show(Product $product, ProductSlot $slot): Response
    {
        return Inertia::render("ProductSlots/Form", [
            'view_only' => true,
            'entity' => $slot,
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product     $product
     * @param ProductSlot $slot
     * @return Response
     */
    public function edit(Product $product, ProductSlot $slot): Response
    {
        return Inertia::render("ProductSlots/Form", [
            'entity' => $slot,
            'product' => $product
        ]);
    }
}
