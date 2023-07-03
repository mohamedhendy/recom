<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Articles\FetchArticlesRequest;
use App\Http\Resources\SaleOrderProduct\SaleOrderProductCollection;

class ArticleController extends Controller
{
    /**
     * @param FetchArticlesRequest $request
     * @return SaleOrderProductCollection
     */
    public function index(FetchArticlesRequest $request): SaleOrderProductCollection
    {
        return new SaleOrderProductCollection($request->getData());
    }
}
