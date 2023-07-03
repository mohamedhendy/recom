<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorDbCreateTables extends Migration
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
            $table->renameColumn('sales_price', 'default_sale_price');
            $table->renameColumn('purchase_price', 'default_purchase_price');
        });
        #endregion products

        #region deployments
        Schema::table('deployments', function (Blueprint $table) {
            $table->index(['identity_type', 'identity_id']);
        });
        #endregion deployments

        #region purchase_orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->nullable();
            $table->string('internal_id')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('creation_year')->nullable();

            $table->unsignedBigInteger('supplier_id');
            $table->index('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedBigInteger('created_by_id');
            $table->index('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->index('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->softDeletes();
            $table->timestamps();

            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion purchase_orders

        #region purchase_order_products
        Schema::create('purchase_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('price', 100, 2)->default(0);
            $table->double('quantity', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('currency')->nullable(); // TODO
            $table->double('billed_quantity', 8, 2)->default(0);
            $table->double('delivered_quantity', 8, 2)->default(0);
            $table->boolean('documents_upload')->default(false);
            $table->float('unit_cost', 100, 2)->default(0);

            $table->unsignedBigInteger('product_id');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('purchase_order_id');
            $table->index('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            $table->unsignedBigInteger('created_by_id');
            $table->index('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->index('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->unique(['id', 'product_id']);

            $table->softDeletes();
            $table->timestamps();

            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion purchase_order_products

        #region suppliers
        Schema::table('suppliers', function (Blueprint $table) {

        });
        #endregion suppliers

        #region sale_orders
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->nullable();
            $table->string('internal_id')->nullable();
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('creation_year')->nullable();

            $table->string('identity_type');
            $table->unsignedBigInteger('identity_id');
            $table->index('identity_id');
            $table->index(['identity_type', 'identity_id']);

            $table->unsignedBigInteger('project_id')->nullable();
            $table->index('project_id');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->unsignedBigInteger('created_by_id');
            $table->index('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->index('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->softDeletes();
            $table->timestamps();

            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion sales

        #region sale_order_products
        Schema::create('sale_order_products', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->float('price', 100, 2)->default(0);
            $table->float('unit_cost', 100, 2)->default(0);
            $table->double('quantity', 8, 2)->default(0);
            $table->double('billed_quantity', 8, 2)->default(0);
            $table->double('delivered_quantity', 8, 2)->default(0);
            $table->string('description')->nullable();
            $table->string('currency')->nullable(); // TODO

            $table->unsignedBigInteger('product_id');
            $table->index('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('purchase_order_product_id')->nullable();
            $table->index('purchase_order_product_id');
            $table->foreign('purchase_order_product_id')->references('id')->on('purchase_order_products');

            $table->unsignedBigInteger('sale_order_id');
            $table->index('sale_order_id');
            $table->foreign('sale_order_id')->references('id')->on('sale_orders');

            $table->unsignedBigInteger('created_by_id');
            $table->index('created_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');

            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->index('updated_by_id');
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->unique(['id', 'product_id']);

            $table->softDeletes();
            $table->timestamps();

            $table->dateTime('created_at')->nullable(false)->change();
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


        // #region inventory
        // Schema::create()
        // #endregion
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

