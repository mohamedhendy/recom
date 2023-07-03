<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RelinkDocumentsToPurchaseOrdersProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // RELINK ALL DOCUMENTS TO PURCHASE ORDERS PORUDCTS INSTEAD OF ARTICLES
        DB::table('has_documents')->where('document_able_type', 'App\\Models\\Article')->update([
            'document_able_type' => 'App\\Models\\PurchaseOrderProduct'
        ]);
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
