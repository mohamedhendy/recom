<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->index('sales_article_identity_id');
        });

        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->foreign('warehouse_product_id')->references('id')->on('warehouse_products');
            $table->index('warehouse_product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
