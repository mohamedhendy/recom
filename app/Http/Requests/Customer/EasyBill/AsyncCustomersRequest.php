<?php

namespace App\Http\Requests\Customer\EasyBill;

use App\Jobs\Customer\EasyBill\AsyncCustomersJob;
use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AsyncCustomersRequest extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     *
     * @returns array customerDetailsToUpdate
     * @throws Exception
     */
    public function async(): array
    {

        // $cacheKey = config('job-status.job_status_cache_key.async_customers_easybill', 'easy_bill_async_customers');
        DB::beginTransaction();
        try {
            // if (Cache::has($cacheKey)) {
            //     $jobUuid = Cache::get($cacheKey);
            // } else {
            // $jobUuid = (string) Str::uuid();

            $customerDetailsToUpdate = AsyncCustomersJob::dispatchNow();
            // dispatch_now(new AsyncCustomersJob(auth()->user()));
            //     Cache::put($cacheKey, $jobUuid);
            // }
            // $jobStatus = config('job-status.model')::where('uuid', $jobUuid)->first();

            // if (!$jobStatus || $jobStatus->is_ended) {
            //     Cache::forget($cacheKey);
            // }

            DB::commit();

            return $customerDetailsToUpdate;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
