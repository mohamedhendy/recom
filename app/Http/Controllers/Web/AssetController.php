<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Assets/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("Assets/Form", [
            'products' => Product::all()
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param Asset $asset
     * @return Response
     */
    public function show(Asset $asset): Response
    {
        return Inertia::render("Assets/Form", [
            'view_only' => true,
            'asset' => $asset,
            'products' => Product::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Asset $asset
     * @return Response
     */
    public function edit(Asset $asset): Response
    {
        return Inertia::render("Assets/Form", [
            'asset' => $asset,
            'products' => Product::all()
        ]);
    }
}
