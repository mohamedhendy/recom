<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeWarehouseTransactionsBelongToProductWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        // check for user 1
        $user = DB::table('users')->find(1);
        if (is_null($user) || $user->id == 0) {
            throw new Exception("need user 1");
        }

        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_product_id')->nullable();
        });

        $oldwarehouseTransactions = DB::table('warehouse_transactions')->get();
        foreach ($oldwarehouseTransactions as $key => $transaction) {
            $productWarehouse = $this->getProductWareHouseInstance($transaction, $user);
            DB::table('warehouse_transactions')
                ->where('id', $transaction->id)
                ->update([
                    'warehouse_product_id' => $productWarehouse->id
                ]);
        }

        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('warehouse_id');
        });
    }

    public function getProductWareHouseInstance($transaction, $user)
    {
        $warehouseProduct = DB::table('warehouse_products')
            ->where([
                ['warehouse_id', $transaction->warehouse_id],
                ['product_id', $transaction->product_id]
            ])->first();

        if (is_null($warehouseProduct) || $warehouseProduct->id == 0) {
            $warehouseProductId = DB::table('warehouse_products')->insertGetId([
                'product_id', $transaction->product_id,
                'warehouse_id' => $transaction->warehouse_id,
                'created_by_id' => $user->id,
            ]);
            $warehouseProduct = DB::table('warehouse_products')
                ->find($warehouseProductId);
        }

        return $warehouseProduct;
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropColumn('warehouse_product_id');
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('warehouse_id')->nullable();
        });
    }
}
