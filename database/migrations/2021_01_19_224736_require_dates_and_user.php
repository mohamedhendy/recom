<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RequireDatesAndUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        // check for user 1
        $user = DB::table('users')->find(1);
        if (is_null($user) || $user->id == 0) {
            throw new Exception("need user 1");
        }

        #region ArticleIdentity
        DB::table('article_identity')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => $user->id
            ]);

        DB::table('article_identity')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('article_identity', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion ArticleIdentity

        #region Article
        DB::table('articles')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('articles')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Article

        #region Category
        DB::table('categories')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('categories')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Category

        #region Company
//        DB::table('companies')
//            ->where('created_by_id', null)
//            ->update([
//                'created_by_id' => 1
//            ]);

        DB::table('companies')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('companies', function (Blueprint $table) {
//            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Company

        #region Customer
        DB::table('customers')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('customers')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Customer

        #region Project
        DB::table('projects')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('projects')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Project

        #region Deployment
        DB::table('deployments')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('deployments')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('deployments', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Deployment

        #region Document
        DB::table('documents')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('documents')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Document

        #region Invoice
        DB::table('purchases')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('purchases')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Invoice

        #region Staff
        DB::table('staffs')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('staffs')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Staff

        #region HasDocument
        DB::table('has_documents')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('has_documents')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('has_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion HasDocument

        #region Supplier
        DB::table('suppliers')
            ->where('created_by_id', null)
            ->update([
                'created_by_id' => 1
            ]);

        DB::table('suppliers')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion Supplier

        #region User
//        DB::table('users')
//            ->where('created_by_id', null)
//            ->update([
//                'created_by_id' => 1
//            ]);

        DB::table('users')
            ->where('created_at', null)
            ->update([
                'created_at' => "2000-01-01 00:00:00"
            ]);

        Schema::table('users', function (Blueprint $table) {
//            $table->unsignedBigInteger('created_by_id')->nullable(false)->change();
            $table->dateTime('created_at')->nullable(false)->change();
        });
        #endregion User

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        #region ArticleIdentity
        Schema::table('article_identity', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion ArticleIdentity

        #region Article
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Article

        #region Category
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Category

        #region Company
        Schema::table('companies', function (Blueprint $table) {
//            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Company

        #region Customer
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Customer

        #region Project
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Project

        #region Deployment
        Schema::table('deployments', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Deployment

        #region Document
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Document

        #region Invoice
        Schema::table('purchases', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Invoice

        #region Staff
        Schema::table('staffs', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Staff

        #region HasDocument
        Schema::table('has_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion HasDocument

        #region Supplier
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion Supplier

        #region User
        Schema::table('users', function (Blueprint $table) {
//            $table->unsignedBigInteger('created_by_id')->nullable(true)->change();
            $table->dateTime('created_at')->nullable(true)->change();
        });
        #endregion User

    }
}
