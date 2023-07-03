<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Inertia\Inertia;
use Inertia\Response;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Suppliers/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("Suppliers/Form");
    }


    /**
     * Display the specified resource.
     *
     * @param Supplier $supplier
     * @return Response
     */
    public function show(Supplier $supplier): Response
    {
        return Inertia::render("Suppliers/Form", [
            'view_only' => true,
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Supplier $supplier
     * @return Response
     */
    public function edit(Supplier $supplier): Response
    {
        return Inertia::render("Suppliers/Form", [
            'supplier' => $supplier
        ]);
    }
}
