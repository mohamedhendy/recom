<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\FetchUsersRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @param FetchUsersRequest $request
     * @return UserCollection
     */
    public function index(FetchUsersRequest $request): UserCollection
    {
        return new UserCollection($request->getData());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @param User              $user
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return $request->handle($user);
    }
}
