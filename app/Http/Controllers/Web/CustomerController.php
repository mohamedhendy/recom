<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\EasyBill\AsyncCustomersRequest;
use App\Models\Customer;
use Exception;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render("Customers/Index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render("Customers/Form");
    }

    /**
     * Display a listing of the resource.
     *
     * @param AsyncCustomersRequest $request
     * @return Response
     * @throws Exception
     */
    public function easyBillUpdate(AsyncCustomersRequest $request): Response
    {
        // $request->validate(['data' => 'required|array', 'data.*.id' => 'required|integer|exists:products,id']);

        return Inertia::render("Customers/EasyBillUpdate")->with([
            'customerDetailsToUpdate' => $request->async()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Customer $customer
     * @return Response
     */
    public function show(Customer $customer): Response
    {
        return Inertia::render("Customers/Form", [
            'view_only' => true,
            'entity' => $customer
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Customer $customer
     * @return Response
     */
    public function edit(Customer $customer): Response
    {
        return Inertia::render("Customers/Form", [
            'entity' => $customer
        ]);
    }
}
