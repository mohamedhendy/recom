<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCostAndStockAmountForOldWarehouseTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        $warehousesTransactions = DB::table('warehouse_transactions')->orderBy('id', 'asc')->get();
        foreach ($warehousesTransactions as $key => $warehouseTransaction) {
            $productWarehouse = DB::table('warehouse_products')->find($warehouseTransaction->warehouse_product_id);

            $availableQty = $this->updateProductAvailableQuantity($warehouseTransaction, $productWarehouse);
            $this->updateProductStockAmount($warehouseTransaction, $availableQty, $productWarehouse);
        }
    }

    /**
     * @param $warehouseTransaction
     * @return int
     * @throws Exception
     */
    private function updateProductAvailableQuantity($warehouseTransaction, $productWarehouse): int
    {

        if ($warehouseTransaction->transaction_type == 'credit')
            $newAvailableQty = $productWarehouse->available_qty - $warehouseTransaction->qty;
        else
            $newAvailableQty = $productWarehouse->available_qty + $warehouseTransaction->qty;

        if ($newAvailableQty < 0) {
            throw new Exception("Product Available Qty Can't be Negative");
        }

        DB::table('warehouse_products')
            ->where('id', $productWarehouse->id)
            ->update([
                'available_qty' => $newAvailableQty
            ]);

        DB::table('warehouse_transactions')
            ->where('id', $warehouseTransaction->id)
            ->update([
                'available_qty' => $newAvailableQty
            ]);

        return $newAvailableQty;
    }


    /**
     * @param     $warehouseTransaction
     * @param int $availableQty
     */
    private function updateProductStockAmount($warehouseTransaction, int $availableQty, $productWarehouse)
    {
        $amount = DB::table('articles')
                ->find($warehouseTransaction->transactionable_id)
                ->cost_price * $warehouseTransaction->qty;

        if ($warehouseTransaction->transaction_type === 'debit') {
            $stockAmount = $productWarehouse->stock_amount + $amount;
        } else {
            $stockAmount = $productWarehouse->stock_amount - $amount;
        }

        $unitCost = $availableQty > 0 ? $stockAmount / $availableQty : $productWarehouse->unit_cost;

        DB::table('warehouse_transactions')
            ->where('id', $warehouseTransaction->id)
            ->update([
                'unit_cost' => $unitCost,
                'stock_amount' => $stockAmount
            ]);

        DB::table('warehouse_products')
            ->where('id', $productWarehouse->id)
            ->update([
                'unit_cost' => $unitCost,
                'stock_amount' => $stockAmount,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cost_and_stock_amount_for_old_warehouse_transactions');
    }
}
