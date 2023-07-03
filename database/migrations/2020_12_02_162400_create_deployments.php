<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeployments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_identity_id')->nullable();

            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('billed_by_id')->nullable();
            $table->integer('identity_id')->nullable();
            $table->string('identity_type')->nullable();
            $table->boolean('billed')->default(false);
            $table->timestamp('billed_at')->nullable();
            $table->string('a_number')->nullable();
            $table->string('o_number')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('license_key')->nullable();
            $table->string('description')->nullable();
            $table->string('storage_location')->nullable();


            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
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
        //
        Schema::dropIfExists('deployments');
    }
}
