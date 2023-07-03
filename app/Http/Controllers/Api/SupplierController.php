<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Suppliers\FetchSuppliersRequest;
use App\Http\Requests\Suppliers\StoreSupplierRequest;
use App\Http\Requests\Suppliers\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierCollection;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchSuppliersRequest $request
     * @return SupplierCollection
     */
    public function index(FetchSuppliersRequest $request): SupplierCollection
    {
        return new SupplierCollection($request->getData());
    }

    public function allSuppliers()
    {
        return Supplier::select('id','number','name')->get()->map(function($item) {
            $newObject = [];
            $newObject['id'] = $item->id;
            $newObject['full_name'] = $item->number ? $item->number . ' - ' .  $item->name : $item->name;
            return $newObject;
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSupplierRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreSupplierRequest $request)
    {
        return $request->store();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSupplierRequest $request
     * @param Supplier              $supplier
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        return $request->update($supplier);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Supplier $supplier
     * @return Response
     */
    public function destroy(Supplier $supplier): Response
    {
        //
    }
}
