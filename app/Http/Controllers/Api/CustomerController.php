<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\QueryFilters\Common\OrderBy;
use App\Http\QueryFilters\Common\Pagination;
use App\Http\QueryFilters\Customer\CustomerListRelationships;
use App\Http\QueryFilters\Customer\CustomerSearchFilter;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequestSingle;
use App\Http\Resources\Customer\CustomerCollection;
use App\Models\Customer;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CustomerCollection
     */
    public function index(Request $request): CustomerCollection
    {
        $result = Pagination::apply(app(Pipeline::class)->through(
            [
                CustomerListRelationships::class,
                CustomerSearchFilter::class,
                OrderBy::class,
            ]
        )->send(Customer::query())->thenReturn());

        return new CustomerCollection($result);
    }


    public function allCustomers()
    {
        return  Customer::get()->map(function($item) {
            $item->full_name = $item->number ? $item->number . ' - ' .  $item->name : $item->name;
            return $item;
        });
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCustomerRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreCustomerRequest $request)
    {
        return $request->handle();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomerRequest $request
     * @param Customer              $customer
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        return $request->handle($customer);
    }


    /**
     * @param UpdateCustomerRequestSingle $request
     * @param Customer                    $customer
     * @return bool
     * @throws Exception
     */
    public function updateSingle(Customer $customer, UpdateCustomerRequestSingle $request): bool
    {
        return $request->handleSingle($customer);
    }
}
