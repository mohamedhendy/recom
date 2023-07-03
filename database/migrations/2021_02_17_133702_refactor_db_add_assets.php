<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefactorDbAddAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial_number')->nullable();
            $table->string('a_number')->nullable();
            $table->string('description')->nullable();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->index('product_id');

            $table->unsignedBigInteger('inventory_product_id')->nullable();
            $table->foreign('inventory_product_id')->references('id')->on('inventory_products');
            $table->index('inventory_product_id');



            $table->unsignedBigInteger('sale_order_product_id')->nullable();
            $table->foreign(['sale_order_product_id', 'product_id'])->references(['id', 'product_id'])->on('sale_order_products');
            $table->index('sale_order_product_id');

            $table->unsignedBigInteger('purchase_order_product_id')->nullable();
            $table->foreign(['purchase_order_product_id', 'product_id'])->references(['id', 'product_id'])->on('purchase_order_products');
            $table->index('purchase_order_product_id');

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

        Schema::table('products', function (Blueprint $table) {
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->json('default_info')->nullable();
        });

         Schema::create('product_slots', function (Blueprint $table) {
             $table->bigIncrements('id');
             $table->string('name');
             $table->integer('number');
             $table->json('default_info')->nullable();

             $table->unsignedBigInteger('product_id');
             $table->foreign('product_id')->references('id')->on('products');
             $table->index('product_id');

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

        Schema::create('deployment_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('info')->nullable();

            $table->unsignedBigInteger('deployment_id');
            $table->foreign('deployment_id')->references('id')->on('deployments');
            $table->index('deployment_id');

            $table->unsignedBigInteger('product_slot_id');
            $table->foreign('product_slot_id')->references('id')->on('product_slots');
            $table->index('product_slot_id');

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

        Schema::table('deployments', function (Blueprint $table) {
            $table->string('address')->nullable();
            $table->string('building')->nullable();
            $table->renameColumn('storage_location', 'room');
            $table->string('exact_position')->nullable();
            $table->json('info')->nullable();
            $table->string('contact')->nullable();

            $table->unsignedBigInteger('asset_id')->nullable();
            $table->foreign('asset_id')->references('id')->on('assets');
            $table->index('asset_id');

            $table->unsignedBigInteger('deployed_slot_id')->nullable();
            $table->foreign('deployed_slot_id')->references('id')->on('deployment_slots');
            $table->index('deployed_slot_id');
        });

        $deployments = DB::table('deployments')->get();

        foreach ($deployments as $deployment) {

            $props = [];
            if($deployment->mac_address != null) {
                array_push($props, $deployment->mac_address);
            }
            if($deployment->license_key != null) {
                array_push($props, $deployment->license_key);
            }
            $json = json_encode($props);

            DB::table('deployments')
                ->where(['id' => $deployment->id])
                ->update(['info' => $json]);
        }

        Schema::table('deployments', function (Blueprint $table) {
            $table->dropColumn('mac_address');
            $table->dropColumn('license_key');
        });

        Schema::create('deployment_slot_connections', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('first_deployment_slot_id');
            $table->foreign('first_deployment_slot_id')->references('id')->on('deployment_slots');
            $table->index('first_deployment_slot_id');

            $table->unsignedBigInteger('second_deployment_slot_id');
            $table->foreign('second_deployment_slot_id')->references('id')->on('deployment_slots');
            $table->index('second_deployment_slot_id');

            $table->index(['first_deployment_slot_id', 'second_deployment_slot_id']);

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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('model');
            $table->dropColumn('manufacturer');
            $table->dropColumn('slots');
        });

        Schema::table('deployments', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('building');
            $table->renameColumn('room', 'storage_location');
            $table->dropColumn('exact_position');
            $table->dropColumn('note');
        });
    }
}
