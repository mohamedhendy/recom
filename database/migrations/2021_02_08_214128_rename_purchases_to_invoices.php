<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenamePurchasesToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('purchases', 'invoices');

        Schema::table('articles', function (Blueprint $table) {
            $table->renameColumn('purchase_id', 'invoice_id');
        });
        Schema::table('article_identity', function (Blueprint $table) {
            $table->renameColumn('purchase_id', 'invoice_id');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('last_purchase_number', 'last_invoice_number');
            $table->renameColumn('purchase_year', 'invoice_year');
            $table->renameColumn('purchase_number_length', 'invoice_number_length');
        });


        DB::statement("ALTER TABLE articles         DROP CONSTRAINT articles_type_check");
        DB::statement("ALTER TABLE article_identity DROP CONSTRAINT article_identity_type_check");
        DB::statement("ALTER TABLE invoices         DROP CONSTRAINT purchases_type_check");


        DB::statement("ALTER TABLE articles         ADD CONSTRAINT articles_type_check         CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
        DB::statement("ALTER TABLE article_identity ADD CONSTRAINT article_identity_type_check CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
        DB::statement("ALTER TABLE invoices         ADD CONSTRAINT invoices_type_check         CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('invoices', 'purchases');

        Schema::table('articles', function (Blueprint $table) {
            $table->renameColumn('invoice_id', 'purchase_id');
        });
        Schema::table('article_identity', function (Blueprint $table) {
            $table->renameColumn('invoice_id', 'purchase_id');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->renameColumn('last_invoice_number', 'last_purchase_number');
            $table->renameColumn('invoice_year', 'purchase_year');
            $table->renameColumn('invoice_number_length', 'purchase_number_length');
        });


        DB::statement("ALTER TABLE articles         DROP CONSTRAINT articles_type_check");
        DB::statement("ALTER TABLE article_identity DROP CONSTRAINT article_identity_type_check");
        DB::statement("ALTER TABLE purchases        DROP CONSTRAINT invoices_type_check");


        DB::statement("ALTER TABLE articles         ADD CONSTRAINT articles_type_check         CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
        DB::statement("ALTER TABLE article_identity ADD CONSTRAINT article_identity_type_check CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
        DB::statement("ALTER TABLE purchases        ADD CONSTRAINT purchases_type_check        CHECK (type::text = ANY (ARRAY['purchase'::character varying, 'beginning-inventory'::character varying]::text[]))");
    }
}
