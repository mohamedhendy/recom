<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Products/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("Products/Form", [
//            'categories' => Category::all(),
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        return Inertia::render("Products/Form", [
            'view_only' => true,
            'entity' => $product,
//            'categories' => Category::all(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Product $product
     * @return Response
     */
    public function edit(Product $product): Response
    {
        return Inertia::render("Products/Form", [
            'entity' => $product,
//            'categories' => Category::all(),
        ]);
    }

    public function deployments(Product $product): Response
    {
        return Inertia::render('Warehouse/Show', [
            'product' => $product
        ]);
    }
}
