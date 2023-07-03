<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateDeliveredQuantityForSaleOrderProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $saleOrderProducts = DB::table('sale_order_products')->get();
        foreach ($saleOrderProducts as $saleOrderProduct) {
            $purchaseOrderProduct = DB::table('purchase_order_products')
                ->where('id', $saleOrderProduct->purchase_order_product_id)
                ->first();

            if ($purchaseOrderProduct) {
                $isTotalBilled = $purchaseOrderProduct->quantity - $purchaseOrderProduct->delivered_quantity === 0 ||
                    $saleOrderProduct->quantity - $saleOrderProduct->billed_quantity === 0;
                $deliveredQuantity = $isTotalBilled ? $saleOrderProduct->quantity : $saleOrderProduct->billed_quantity;
                DB::table('sale_order_products')->where('id', $saleOrderProduct->id)->update([
                    'delivered_quantity' => $deliveredQuantity
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
