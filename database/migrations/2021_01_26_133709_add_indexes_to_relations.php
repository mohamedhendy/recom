<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        SELECT c.conrelid::regclass AS "table",
        -- list of key column names in order
        string_agg(a.attname, ',' ORDER BY x.n) AS columns,
        pg_catalog.pg_size_pretty(
            pg_catalog.pg_relation_size(c.conrelid)
        ) AS size,
        c.conname AS constraint,
        c.confrelid::regclass AS referenced_table
        FROM pg_catalog.pg_constraint c
        -- enumerated key column numbers per foreign key
        CROSS JOIN LATERAL
            unnest(c.conkey) WITH ORDINALITY AS x(attnum, n)
        -- name for each key column
        JOIN pg_catalog.pg_attribute a
            ON a.attnum = x.attnum
            AND a.attrelid = c.conrelid
        WHERE NOT EXISTS
        -- is there a matching index for the constraint?
            (SELECT 1 FROM pg_catalog.pg_index i
                WHERE i.indrelid = c.conrelid
        -- the first index columns must be the same as the
        -- key columns, but order doesn't matter
                AND (i.indkey::smallint[])[0:cardinality(c.conkey)-1]
                OPERATOR(pg_catalog.@>) c.conkey)
            AND c.contype = 'f'
        GROUP BY c.conrelid, c.conname, c.confrelid
        ORDER BY pg_catalog.pg_relation_size(c.conrelid) DESC;
        */
        Schema::table('article_identity', function (Blueprint $table) {
            $table->index('article_id');
            $table->index('identity_id');
            $table->index('identity_type');
            $table->index('supplier_id');
            $table->index('type');
            $table->index('project_id');
            $table->index('purchase_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });


        Schema::table('articles', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('product_id');
            $table->index('purchase_id');
            $table->index('subcategory_id');
            $table->index('type');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('parent_category_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('type');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('deployments', function (Blueprint $table) {
            $table->index('article_identity_id');
            $table->index('billed_by_id');
            $table->index('identity_id');
            $table->index('identity_type');
            $table->index('supplier_id');
            $table->index('type');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('has_documents', function (Blueprint $table) {
            $table->index('document_able_id');
            $table->index('document_able_type');
            $table->index('document_id');
            $table->index('type');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->index('customer_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('supplier_id');
            $table->index('type');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('staff_id');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('warehouse_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });

        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('transaction_type');
            $table->index('transactionable_type');
            $table->index('transactionable_id');
            $table->index('warehouse_id');

            $table->index('created_by_id');
            $table->index('updated_by_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_identity', function (Blueprint $table) {
            $table->dropIndex('article_identity_article_id_index');
            $table->dropIndex('article_identity_identity_id_index');
            $table->dropIndex('article_identity_identity_type_index');
            $table->dropIndex('article_identity_supplier_id_index');
            $table->dropIndex('article_identity_type_index');
            $table->dropIndex('article_identity_project_id_index');
            $table->dropIndex('article_identity_purchase_id_index');

            $table->dropIndex('article_identity_created_by_id_index');
            $table->dropIndex('article_identity_updated_by_id_index');
        });


        Schema::table('articles', function (Blueprint $table) {
            $table->dropIndex('articles_category_id_index');
            $table->dropIndex('articles_product_id_index');
            $table->dropIndex('articles_purchase_id_index');
            $table->dropIndex('articles_subcategory_id_index');
            $table->dropIndex('articles_type_index');

            $table->dropIndex('articles_created_by_id_index');
            $table->dropIndex('articles_updated_by_id_index');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_parent_category_id_index');

            $table->dropIndex('categories_created_by_id_index');
            $table->dropIndex('categories_updated_by_id_index');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('customers_type_index');

            $table->dropIndex('customers_created_by_id_index');
            $table->dropIndex('customers_updated_by_id_index');
        });

        Schema::table('deployments', function (Blueprint $table) {
            $table->dropIndex('deployments_article_identity_id_index');
            $table->dropIndex('deployments_billed_by_id_index');
            $table->dropIndex('deployments_identity_id_index');
            $table->dropIndex('deployments_identity_type_index');
            $table->dropIndex('deployments_supplier_id_index');
            $table->dropIndex('deployments_type_index');

            $table->dropIndex('deployments_created_by_id_index');
            $table->dropIndex('deployments_updated_by_id_index');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('documents_created_by_id_index');
            $table->dropIndex('documents_updated_by_id_index');
        });

        Schema::table('has_documents', function (Blueprint $table) {
            $table->dropIndex('has_documents_document_able_id_index');
            $table->dropIndex('has_documents_document_able_type_index');
            $table->dropIndex('has_documents_document_id_index');
            $table->dropIndex('has_documents_type_index');

            $table->dropIndex('has_documents_created_by_id_index');
            $table->dropIndex('has_documents_updated_by_id_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_category_id_index');

            $table->dropIndex('products_created_by_id_index');
            $table->dropIndex('products_updated_by_id_index');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex('projects_customer_id_index');

            $table->dropIndex('projects_created_by_id_index');
            $table->dropIndex('projects_updated_by_id_index');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex('purchases_supplier_id_index');
            $table->dropIndex('purchases_type_index');

            $table->dropIndex('purchases_created_by_id_index');
            $table->dropIndex('purchases_updated_by_id_index');
        });

        Schema::table('staffs', function (Blueprint $table) {
            $table->dropIndex('staffs_created_by_id_index');
            $table->dropIndex('staffs_updated_by_id_index');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex('suppliers_created_by_id_index');
            $table->dropIndex('suppliers_updated_by_id_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_company_id_index');
            $table->dropIndex('users_staff_id_index');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropIndex('warehouses_created_by_id_index');
            $table->dropIndex('warehouses_updated_by_id_index');
        });

        Schema::table('warehouse_products', function (Blueprint $table) {
            $table->dropIndex('warehouse_products_product_id_index');
            $table->dropIndex('warehouse_products_warehouse_id_index');

            $table->dropIndex('warehouse_products_created_by_id_index');
            $table->dropIndex('warehouse_products_updated_by_id_index');
        });

        Schema::table('warehouse_transactions', function (Blueprint $table) {
            $table->dropIndex('warehouse_transactions_product_id_index');
            $table->dropIndex('warehouse_transactions_transaction_type_index');
            $table->dropIndex('warehouse_transactions_transactionable_type_index');
            $table->dropIndex('warehouse_transactions_transactionable_id_index');
            $table->dropIndex('warehouse_transactions_warehouse_id_index');

            $table->dropIndex('warehouse_transactions_created_by_id_index');
            $table->dropIndex('warehouse_transactions_updated_by_id_index');
        });
    }
}
