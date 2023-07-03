<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleIdentity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('article_identity', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->float('sales_price', 20, 4)->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->bigInteger('delivered_quantity')->default(0);
            $table->text('description')->nullable();
            $table->bigInteger('billed_quantity')->default(0);
            $table->morphs('identity');

            $table->unsignedBigInteger('article_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->nullable();
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
        Schema::dropIfExists('article_identity');
    }
}
