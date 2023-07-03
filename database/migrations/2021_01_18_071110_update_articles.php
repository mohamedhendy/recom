<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->enum('type', ['purchase', 'beginning-inventory'])->default('purchase');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable();
            $table->enum('type', ['purchase', 'beginning-inventory'])->default('purchase');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('article_identity', function (Blueprint $table) {
            $table->enum('type', ['purchase', 'beginning-inventory'])->default('purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('type');
        });

        Schema::table('article_identity', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
