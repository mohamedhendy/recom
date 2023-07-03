<?php

namespace App\Http\Requests\SaleOrder;

use App\Jobs\Assets\DeployAssetsJob;
use App\Jobs\EasyBill\HandleEasyBillJob;
use App\Jobs\SaleOrder\AddSaleOrderProductBilledQuantityJob;
use App\Jobs\Warehouse\CreateProductWareHouseTransactionJob;
use App\Models\SaleOrderProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UpdateSaleOrderBillingStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
            'pushed_quantity' => 'required|integer|min:1',
            'assets' => 'nullable|array',
            'assets.*.serial_number' => 'nullable|string',
            'assets.*.description' => 'nullable|string',
            'assets.*.id' => 'nullable|integer|exists:assets,id',
            'assets.*.a_number' => 'nullable|string',
            'push_to_easy_bill' => 'required|boolean',
            'draft_invoice_id' => 'required_if:push_to_easy_bill,true'
        ];
    }

    public function push(SaleOrderProduct $saleOrderProduct)
    {

        return DB::transaction(function () use ($saleOrderProduct) {
            $billedQuantity = $this->input('pushed_quantity');
            // UPDATE BILLED_QUANTITY COLUMN IN SALE_ORDER_PRODUCT
            AddSaleOrderProductBilledQuantityJob::dispatchNow($saleOrderProduct, $billedQuantity);
            // CREATE WAREHOUSE TRANSACTION
            CreateProductWareHouseTransactionJob::dispatchNow(null, $saleOrderProduct->product, $saleOrderProduct, (float)$billedQuantity, 'credit', auth()->user());
            // REGISTER ASSETS AND CREATE DEPLOYMENTS
            $assets = DeployAssetsJob::dispatchNow($saleOrderProduct, $this->input('assets'));
            // PUSH TO EASYBILL IF ENABLED
            HandleEasyBillJob::dispatchNow(!$this->input('push_to_easy_bill'), $saleOrderProduct, $assets, $this->input('draft_invoice_id'));
        });
    }
}
