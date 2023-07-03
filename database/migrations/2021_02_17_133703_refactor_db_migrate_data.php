<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RefactorDbMigrateData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        // create product for not related deployments
        $generalProductId = DB::table('products')->insertGetId(['name' => 'unknown product']);

        // fill suppliers
        $iWOsupplier = DB::table('invoices')
            ->where(['supplier_id' => null])
            ->get();

        if (sizeof($iWOsupplier) != 0) {
            $naSupplier = DB::table('suppliers')->insertGetId([
                'name' => 'n/a',
                'number' => '0',
                'created_by_id' => '1',
                'created_at' => now(),
            ]);

            DB::table('invoices')
                ->where(['supplier_id' => null])
                ->update(['supplier_id' => $naSupplier]);
        }


        // clean warehouses
        $oldWarehouseProducts = DB::table('warehouse_products')->get();
        DB::table('warehouse_products')
            ->update([
                'stock_amount' => 0,
                'available_qty' => 0,
                'unit_cost' => 0
            ]);

        $oldWarehouseTransactions = DB::table('warehouse_transactions')->get();
        DB::table('warehouse_transactions')
            ->truncate();


        // loop invoices
        $invoicesPurchase = DB::table('invoices')->get();

        $i = 0;
        $length = sizeof($invoicesPurchase);
        $lengthLength = strlen($length);
        $max = ini_get('memory_limit');

        foreach ($invoicesPurchase as $invoice) {
            $i++;
            if ($i == 1 || $i % 50 == 0 || $i == $length) {
                $this->display_memory($i, $lengthLength, $length, $max);
            }

            if ($invoice->type === 'purchase') {
                $this->createPurchase($invoice);
            } else {
                throw new Exception("no purchase");
            }
        }

        // do missing deployments
        $deployments = DB::table('deployments')->get();
        foreach ($deployments as $deployment) {
            if ($deployment->article_identity_id != null) {
                if ($deployment->asset_id != null) {
                    continue;
                }

                throw new Exception("article identity missed in invoices");
            }

            $this->updateDeployment($deployment, $generalProductId, null, null, $deployment);
        }

        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')->where('id', $product->id)->update([
                'default_sale_price' => $product->default_sale_price / 100,
                'default_purchase_price' => $product->default_purchase_price / 100,
            ]);
        }
        DB::statement("SELECT setval(pg_get_serial_sequence('purchase_orders', 'id'), coalesce(max(id)+1, 1), false) FROM purchase_orders");
        DB::statement("SELECT setval(pg_get_serial_sequence('purchase_order_products', 'id'), coalesce(max(id)+1, 1), false) FROM purchase_order_products");
        DB::statement("SELECT setval(pg_get_serial_sequence('sale_orders', 'id'), coalesce(max(id)+1, 1), false) FROM sale_orders");
        DB::statement("SELECT setval(pg_get_serial_sequence('sale_order_products', 'id'), coalesce(max(id)+1, 1), false) FROM sale_order_products");
        DB::statement("SELECT setval(pg_get_serial_sequence('warehouse_transactions', 'id'), coalesce(max(id)+1, 1), false) FROM warehouse_transactions");

        $this->checkInventory($oldWarehouseTransactions, $oldWarehouseProducts);
    }

    private function display_memory($i, $lengthLength, $length, $max)
    {
        $mem = intval(memory_get_usage(true) / 1024 / 1024);

        print "\n" . sprintf('%' . $lengthLength . 'd', $i) . "/" . $length . " Mem: " . $mem . "MB / " . $max;
    }

    /**
     * @param $invoice
     * @throws Exception
     */
    private function createPurchase($invoice)
    {
        if ($invoice->supplier_id == null) {
            throw new Exception("no supplier");
        }

        DB::table('purchase_orders')->insert([
            'id' => $invoice->id,
            'supplier_id' => $invoice->supplier_id,
            'number' => $invoice->number,
            'internal_id' => $invoice->internal_id,
            'issue_date' => $invoice->issue_date,
            'due_date' => $invoice->due_date,
            'status' => $invoice->status,
            'creation_year' => $invoice->creation_year,
            'created_by_id' => $invoice->created_by_id,
            'updated_by_id' => $invoice->updated_by_id,
            'created_at' => $invoice->created_at,
            'updated_at' => $invoice->updated_at,
        ]);
        $articles = DB::table('articles')->where('invoice_id', $invoice->id)->get();
        foreach ($articles as $article) {
            if ($article->type != "purchase") {
                throw new Exception("article type is not purchase");
            }

            $this->createPurchaseOrderProduct($invoice, $article);
        }
    }

    /**
     * @param $invoice
     * @param $article
     * @throws Exception
     */
    private function createPurchaseOrderProduct($invoice, $article)
    {
        if ($article->product_id == null) {
            $newProductId = DB::table('products')->insertGetId([
                'name' => $article->name,
                'category_id' => $article->subcategory_id ? $article->subcategory_id : $article->category_id,
                'created_by_id' => '1',
                'created_at' => $article->created_at,
            ]);

            DB::table('articles')
                ->where(['id' => $article->id])
                ->update(['product_id' => $newProductId]);
            $article->product_id = $newProductId;
        }

        $product = DB::table('products')
            ->find($article->product_id);
        $warehouse = DB::table('warehouses')
            ->first();
        $totalWarehousesUnitCostAvarage = $this
            ->getProductUnitCost($product);
        $articleDeliveredQuantity = DB::table('article_identity')
            ->where('article_id', $article->id)
            ->sum('delivered_quantity');
        $articleBilledQuantity = DB::table('article_identity')
            ->where('article_id', $article->id)
            ->sum('billed_quantity');


        $totalWarehousesUnitCostAvarage = $totalWarehousesUnitCostAvarage <= 0 ? $article->cost_price / 100 : $totalWarehousesUnitCostAvarage;
        $articleDeliveredQuantity = $articleDeliveredQuantity > $article->quantity ? $article->quantity : $articleDeliveredQuantity;
        $articleBilledQuantity = $articleBilledQuantity > $article->quantity ? $article->quantity : $articleBilledQuantity;


        DB::table('purchase_order_products')->insert([
            'id' => $article->id,
            'purchase_order_id' => $invoice->id,
            'price' => $article->cost_price / 100,
            'unit_cost' => round((float)$totalWarehousesUnitCostAvarage, 2),
            'currency' => $article->currency_code,
            'quantity' => $article->quantity,
            'delivered_quantity' => $articleDeliveredQuantity,
            'billed_quantity' => $articleBilledQuantity,
            'description' => $article->description,
            'product_id' => $article->product_id,
            'documents_upload' => $article->documents_upload,
            'created_by_id' => $article->created_by_id,
            'updated_by_id' => $article->updated_by_id,
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at,
        ]);
        $purchaseOrderProduct = DB::table('purchase_order_products')->find($article->id);
        $this->createInventoryTransaction($product, $warehouse, $purchaseOrderProduct, $articleDeliveredQuantity, 'debit', 'App\\Models\\PurchaseOrderProduct');
        $this->createPurchaseOrderProductRelatedSalesOrders($invoice, $article, $warehouse, $product, $purchaseOrderProduct->unit_cost);
    }

    private function getProductUnitCost($product)
    {
        $totalWarehousesUnitCostAvarage = 0;
        if ($product) {
            $totalUnitCost = DB::table('warehouse_products')->where('product_id', $product->id)->sum('unit_cost');
            $warehousesCount = DB::table('warehouse_products')->where([['product_id', $product->id]])->count();
            $totalWarehousesUnitCostAvarage = $warehousesCount > 0 ? round((float)($totalUnitCost / $warehousesCount), 2) : $totalUnitCost;
        }
        return $totalWarehousesUnitCostAvarage;
    }

    private function createInventoryTransaction($product, $warhouse, $transactionable, $quantity, $transctionType, $model = "")
    {
        if ($quantity > 0) {
            $productWarehouse = $this->getProductWareHouseInstance($product->id, $warhouse->id);
            $stockAvailableQuantity = $productWarehouse->available_qty;
            $stockTotalAmount = $productWarehouse->stock_amount;
            $stockUnitCost = $productWarehouse->unit_cost;

            if ($transctionType == 'credit') {
                $transactionQuantity = $stockAvailableQuantity - $quantity < 0 ? $stockAvailableQuantity : $quantity;
            } else {

                $transactionQuantity = $quantity;
            }

            if ($transactionQuantity > 0) {
                $transactionId = DB::table('warehouse_transactions')->insertGetId([
                    'warehouse_product_id' => $productWarehouse->id,
                    'transaction_type' => $transctionType,
                    "transactionable_type" => $model,
                    'transactionable_id' => $transactionable->id,
                    'qty' => $transactionQuantity,
                    'created_by_id' => 1,
                    'created_at' => $transactionable->created_at,
                    'updated_at' => $transactionable->updated_at,
                ]);
                $transaction = DB::table('warehouse_transactions')->find($transactionId);


                if ($transctionType == 'credit') {
                    $newProductWarehouseAvailableQty = (float)$stockAvailableQuantity - $transactionQuantity;
                    $newProductWarehouseStockAmount = $stockUnitCost * $newProductWarehouseAvailableQty;

                } else {
                    $transactionStockAmount = (float)$transactionable->price * (float)$quantity;

                    $newProductWarehouseAvailableQty = (float)$stockAvailableQuantity + $quantity;
                    $newProductWarehouseStockAmount = (float)$stockTotalAmount + $transactionStockAmount;

                    $stockUnitCost = round((float)($newProductWarehouseStockAmount / $newProductWarehouseAvailableQty), 2);

                }


                DB::table('warehouse_products')->where('id', $productWarehouse->id)->update([
                    'unit_cost' => $stockUnitCost,
                    'stock_amount' => $newProductWarehouseStockAmount,
                    'available_qty' => $newProductWarehouseAvailableQty
                ]);
                DB::table('warehouse_transactions')->where('id', $transaction->id)->update([
                    'unit_cost' => $stockUnitCost,
                    'stock_amount' => $newProductWarehouseStockAmount,
                    'available_qty' => $newProductWarehouseAvailableQty
                ]);

            }


        }
    }

    public function getProductWareHouseInstance($productId, $warehouseId)
    {
        $productWarehouse = DB::table('warehouse_products')->where([['product_id', $productId], ['warehouse_id', $warehouseId]])->first();

        if (!$productWarehouse) {
            $productWarehouseId = DB::table('warehouse_products')->insertGetId([
                'warehouse_id' => $warehouseId,

                'product_id' => $productId,
                'created_by_id' => 1
            ]);

            $productWarehouse = DB::table('warehouse_products')->find($productWarehouseId);
        }

        return $productWarehouse;
    }

    private function createPurchaseOrderProductRelatedSalesOrders($invoice, $article, $warehouse, $product, $unitCost)
    {
        $articleIdentities = DB::table('article_identity')->where('article_id', $article->id)->get();

        foreach ($articleIdentities as $articleIdentity) {
            $deliveredQuantity = $articleIdentity->delivered_quantity;
            $billedQuantity = $articleIdentity->billed_quantity;
            $quantity = $articleIdentity->quantity;

            if ($deliveredQuantity > $quantity) {
                $deliveredQuantity = $quantity;
            }

            if ($billedQuantity > $quantity) {
                $billedQuantity = $quantity;
            }


            if ($articleIdentity->type != "purchase") {
                throw new Exception("articleIdentity type is not purchase");
            }


            $saleOrderId = DB::table('sale_orders')->insertGetId([
                'identity_type' => $articleIdentity->identity_type,
                'identity_id' => $articleIdentity->identity_id,
                'number' => $invoice->number,
                'internal_id' => $invoice->internal_id,
                'issue_date' => $invoice->issue_date,
                'due_date' => $invoice->due_date,
                'status' => $invoice->status,
                'creation_year' => $invoice->creation_year,
                'project_id' => $articleIdentity->project_id,
                'created_by_id' => $articleIdentity->created_by_id,
                'updated_by_id' => $articleIdentity->updated_by_id,
                'created_at' => $articleIdentity->created_at,
                'updated_at' => $articleIdentity->updated_at,
            ]);

            $saleOrderProductId = DB::table('sale_order_products')->insertGetId([
                'sale_order_id' => $saleOrderId,
                'price' => $articleIdentity->sales_price == 0 ? 0 : $articleIdentity->sales_price / 100,
                'unit_cost' => $unitCost,
                'currency' => $article->currency_code,
                'quantity' => $quantity,
                'billed_quantity' => $billedQuantity,
                'delivered_quantity' => $deliveredQuantity,
                'description' => $articleIdentity->description,
                'product_id' => $article->product_id,
                'purchase_order_product_id' => $article->id,
                'created_by_id' => $articleIdentity->created_by_id,
                'updated_by_id' => $articleIdentity->updated_by_id,
                'created_at' => $articleIdentity->created_at,
                'updated_at' => $articleIdentity->updated_at,
            ]);

            $saleOrderProduct = DB::table('sale_order_products')->find($saleOrderProductId);
            $this->createInventoryTransaction($product, $warehouse, $saleOrderProduct, $billedQuantity, 'credit', 'App\\Models\\SaleOrderProduct');
            $this->refactorDeployments($articleIdentity, $article, $saleOrderProduct);

        }
    }

    private function refactorDeployments($articleIdentity, $article, $saleOrderProduct)
    {
        $deployments = DB::table('deployments')
            ->where(
                'article_identity_id', $articleIdentity->id
            )->get();


        foreach ($deployments as $deployment) {

            $this->updateDeployment($deployment, $article->product_id, $saleOrderProduct->id, $article->id, $articleIdentity);
        }
    }

    private function updateDeployment($deployment, $productId, $saleOrderProductId, $purchaseOrderProductId, $metaInfo)
    {
        $assetId = DB::table('assets')->insertGetId([
            'a_number' => $deployment->a_number,
            'serial_number' => $deployment->serial_number,
            'description' => $deployment->description,
            'product_id' => $productId,
            'sale_order_product_id' => $saleOrderProductId,
            'purchase_order_product_id' => $purchaseOrderProductId,
            'created_by_id' => $metaInfo->created_by_id,
            'updated_by_id' => $metaInfo->updated_by_id,
            'created_at' => $metaInfo->created_at,
            'updated_at' => $metaInfo->updated_at,
        ]);

        DB::table('deployments')
            ->where(['id' => $deployment->id])
            ->update([
                'asset_id' => $assetId
            ]);
    }

    private function checkInventory(Collection $oldWarehouseTransactions, Collection $oldWarehouseProducts)
    {
//        foreach ($oldWarehouseProducts as $oldWarehouseProduct) {
//            $newWarehouseProduct = DB::table('warehouse_products')->find($oldWarehouseProduct->id);
//            if (
//                ($newWarehouseProduct->stock_amount != $oldWarehouseProduct->stock_amount
//                    || $newWarehouseProduct->available_qty != $oldWarehouseProduct->available_qty
//                    || $newWarehouseProduct->unit_cost != $oldWarehouseProduct->unit_cost)
//                && $oldWarehouseProduct->unit_cost > 0
//                && $oldWarehouseProduct->stock_amount >= 0
//                && $newWarehouseProduct->unit_cost > 0
//                && $newWarehouseProduct->available_qty == $oldWarehouseProduct->available_qty
//            ) {
//                dd($newWarehouseProduct, $oldWarehouseProduct);
//                throw new Exception("warehouse product error");
//            }
//        }


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
