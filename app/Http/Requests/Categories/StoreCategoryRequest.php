<?php

namespace App\Http\Requests\Categories;

use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StoreCategoryRequest extends FormRequest
{
    use CategoryFormRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $data = $this->only(
                "name",
                "parent_category_id",
            );
            $data['created_by_id'] = $this->user()->id;
            Category::create($data);
            DB::commit();
            return redirect('/categories');
        } catch (QueryException | ValidationException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
