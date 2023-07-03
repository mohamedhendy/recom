<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        #region products
        Schema::table('products', function (Blueprint $table) {

        });
        #endregion products

        #region assets
        Schema::table('assets', function (Blueprint $table) {

        });
        #endregion assets

        #region deployments
        Schema::table('deployments', function (Blueprint $table) {

        });
        #endregion deployments

        #region purchase_orders
        Schema::table('purchase_order_products', function (Blueprint $table) {

        });
        #endregion purchase_orders

        #region purchase_order_items
        Schema::table('purchase_order_items', function (Blueprint $table) {

        });
        #endregion purchase_order_items

        #region suppliers
        Schema::table('suppliers', function (Blueprint $table) {

        });
        #endregion suppliers

        #region sale_orders
        Schema::table('sale_orders', function (Blueprint $table) {

        });
        #endregion sales

        #region sale_order_products
        Schema::table('sale_order_products', function (Blueprint $table) {

        });
        #endregion sale_order_products

        #region customers
        Schema::table('customers', function (Blueprint $table) {

        });
        #endregion customers

        #region projects
        Schema::table('projects', function (Blueprint $table) {

        });
        #endregion projects
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

