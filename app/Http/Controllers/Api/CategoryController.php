<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\FetchCategoriesRequest;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param FetchCategoriesRequest $request
     * @return CategoryCollection
     */
    public function index(FetchCategoriesRequest $request): CategoryCollection
    {
        return new CategoryCollection($request->getData());
    }

    public function allCategories()
    {
        return Category::select('id','name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCategoryRequest $request
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function store(StoreCategoryRequest $request)
    {
        return $request->handle();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param Category              $category
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return $request->handle($category);
    }
}
