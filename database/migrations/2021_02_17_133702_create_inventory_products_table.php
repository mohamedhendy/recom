<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_product_id');
            $table->decimal('unit_cost', 50, 2)->default(0);
            $table->double('quantity', 8, 2)->default(0);
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->foreign('inventory_id')->references('id')->on('inventories');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('warehouse_product_id')->references('id')->on('warehouse_products');
            $table->enum('transaction', ['increase', 'descrease', 'set'])->default('set');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_products');
    }
}
