<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefactorDbDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deployments', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable(false)->change();

            $table->dropColumn('article_identity_id');
            $table->dropColumn('billed');
            $table->dropColumn('billed_by_id');
            $table->dropColumn('billed_at');
            $table->dropColumn('serial_number');
            $table->dropColumn('a_number');
            $table->dropColumn('sales_article_identity_id');
            $table->dropColumn('description');
        });

        Schema::drop('article_identity');
        Schema::drop('articles');
        Schema::drop('invoices');
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

