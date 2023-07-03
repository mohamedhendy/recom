<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RemoveDeplicatedWarehouseProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $deplicatedProducts = DB::table('warehouse_products')->select('product_id')->groupBy('product_id')->having(DB::raw('count(id)'), '>=', 2)->pluck('product_id');
        $data = DB::table('warehouse_products')->whereIn('product_id', $deplicatedProducts->toArray())->orderBy('product_id')->get();
        $pushedProductWarehouse = collect();
        foreach ($data as $productWarehouse) {

            if (!$pushedProductWarehouse->has($productWarehouse->product_id)) {
                $unitCost = DB::table('warehouse_products')->where('product_id', $productWarehouse->product_id)->sum('unit_cost') / DB::table('warehouse_products')->where('product_id', $productWarehouse->product_id)->count();
                $availableQuantity = DB::table('warehouse_products')->where('product_id', $productWarehouse->product_id)->sum('available_qty');
                DB::table('warehouse_products')->where('id', $productWarehouse->id)->update([
                    'unit_cost' => $unitCost,
                    'available_qty' => $availableQuantity,
                    'stock_amount' => $unitCost * $availableQuantity

                ]);
                $pushedProductWarehouse->prepend($productWarehouse->id,$productWarehouse->product_id);
            }else {
                $warehouseProductId = $pushedProductWarehouse->get($productWarehouse->product_id);
                DB::table('warehouse_transactions')->where('warehouse_product_id',$productWarehouse->id)->update([
                    'warehouse_product_id' => $warehouseProductId
                ]);
                DB::table('inventory_products')->where('warehouse_product_id',$productWarehouse->id)->update([
                    'warehouse_product_id' => $warehouseProductId
                ]);
                DB::table('warehouse_products')->where('id',$productWarehouse->id)->delete();
            }
        }
        $deplicatedProducts = DB::table('warehouse_products')->select('product_id')->groupBy('product_id')->having(DB::raw('count(id)'), '>=', 2)->count();
        if($deplicatedProducts) {
           throw new Exception('deplicated warehouse products');
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
