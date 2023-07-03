<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateUserRequest extends FormRequest
{
    use UserFormRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->isAdmin();
    }

    /**
     * @param User $user
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle(User $user)
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
                "name",
                'email',
                'role'
            );
            $user->update($data);
            DB::commit();
            return redirect('/users');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
