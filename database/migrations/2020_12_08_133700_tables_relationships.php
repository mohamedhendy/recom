<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablesRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::table("categories", function (Blueprint $table) {
            $table->foreign('parent_category_id')->references('id')->on('categories');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
        });


        Schema::table("users", function (Blueprint $table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });


        Schema::table("customers", function (Blueprint $table) {
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
        });


        Schema::table("purchases", function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
        });


        Schema::table("articles", function (Blueprint $table) {
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('subcategory_id')->references('id')->on('categories');
        });


        Schema::table("deployments", function (Blueprint $table) {
            $table->foreign('billed_by_id')->references('id')->on('users');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('article_identity_id')->references('id')->on('article_identity');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
        });


        Schema::table("documents", function (Blueprint $table) {
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
        });

        Schema::table("has_documents", function (Blueprint $table) {
            $table->foreign('document_id')->references('id')->on('documents');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');

        });


        Schema::table("article_identity", function (Blueprint $table) {
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('purchase_id')->references('id')->on('purchases');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
        });

        Schema::table("projects", function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
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
    }
}
