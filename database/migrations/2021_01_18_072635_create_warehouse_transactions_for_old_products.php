<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWarehouseTransactionsForOldProducts extends Migration
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

        $articles = DB::table('articles')->get();
        $mainWarehouse = DB::table('warehouses')->first();
        foreach ($articles as $article) {
            $product = DB::table('products')
                ->where([
                    ['name', $article->name],
                    ['category_id', $article->subcategory_id]
                ])->first();

            DB::table('articles')
                ->where('id', $article->id)
                ->update([
                    'product_id' => $product->id
                ]);

            $articleIdentities = DB::table('article_identity')
                ->where([
                    ['article_id', $article->id],
                ]);

            $deliveredQuantity = $articleIdentities->sum('delivered_quantity');
            $billedQuantity = $articleIdentities->sum('billed_quantity');

            if ($deliveredQuantity > 0) {
                $this->createProductWareHouseTransaction($mainWarehouse, $product, $article, $deliveredQuantity, 'debit', $user->id);
                if ($billedQuantity > 0)
                    $this->createProductWareHouseTransaction($mainWarehouse, $product, $article, $billedQuantity, 'credit', $user->id);
            }
        }
    }

    /**
     * @param     $warehouse
     * @param     $product
     * @param     $article
     * @param     $billedQuantity
     * @param     $transactionType
     * @param int $userId
     * @throws Exception
     */
    private function createProductWareHouseTransaction($warehouse, $product, $article, $billedQuantity, $transactionType, $userId)
    {
        $productWarehouse = DB::table('warehouse_products')
            ->where([
                ['warehouse_id', $warehouse->id],
                ['product_id', $product->id]
            ])->first();

        if (is_null($productWarehouse)) {
            $productWarehouseId = DB::table('warehouse_products')
                ->insertGetId([
                    'product_id' => $product->id,
                    'warehouse_id' => $warehouse->id,
                    'created_by_id' => $userId
                ]);

            $productWarehouse = DB::table('warehouse_products')
                ->find($productWarehouseId);
        }

        DB::table('warehouse_transactions')->insert([
            'transactionable_id' => $article->id,
            'transactionable_type' => '\App\Models\Articles',
            'product_id' => $productWarehouse->product_id,
            'warehouse_id' => $productWarehouse->warehouse_id,
            'transaction_type' => $transactionType,
            'qty' => $billedQuantity,
            'created_by_id' => $userId
        ]);

        $this->updateProductAvailableQuantity($productWarehouse, $transactionType, $billedQuantity);
    }

    /**
     * @param        $productWarehouse
     * @param string $transactionType
     * @param int    $billedQuantity
     * @throws Exception
     */
    private function updateProductAvailableQuantity($productWarehouse, string $transactionType, int $billedQuantity)
    {
        if ($transactionType == 'credit')
            $newAvailableQty = $productWarehouse->available_qty - $billedQuantity;
        else
            $newAvailableQty = $productWarehouse->available_qty + $billedQuantity;

        if ($newAvailableQty < 0) {
            throw new Exception("Product Available Qty Can't be Negative");
        }

        DB::table('warehouse_products')
            ->where('id', $productWarehouse->id)
            ->update([
                'available_qty' => $newAvailableQty
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('warehouse_transactions')->truncate();
        DB::table('warehouse_products')->truncate();
    }
}
