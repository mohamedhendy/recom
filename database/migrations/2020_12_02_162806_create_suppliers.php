<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number');
            $table->string('name');
            $table->text('description')->nullable();

            // Address
            $table->string('address_salutation')->nullable();
            $table->string('address_name')->nullable();
            $table->string('address_first_name')->nullable();
            $table->string('address_addon1')->nullable();
            $table->string('address_addon2')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_zip_code', 20)->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_country')->nullable();

            //Tax
            $table->string('tax_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('payment_terms')->nullable();


            // Contact details
            $table->string('contact_name1')->nullable();
            $table->string('contact_function1')->nullable();
            $table->string('contact_name2')->nullable();
            $table->string('contact_function2')->nullable();
            $table->string('contact_details1_phone1')->nullable();
            $table->string('contact_details1_phone2')->nullable();
            $table->string('contact_details1_fax')->nullable();
            $table->string('contact_details1_mobile')->nullable();
            $table->string('contact_details1_email')->nullable();
            $table->string('contact_details1_homepage')->nullable();
            $table->string('contact_details2_phone1')->nullable();
            $table->string('contact_details2_phone2')->nullable();
            $table->string('contact_details2_fax')->nullable();
            $table->string('contact_details2_mobile')->nullable();
            $table->string('contact_details2_email')->nullable();
            $table->string('contact_details2_homepage')->nullable();

            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_bic')->nullable();

            //
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('suppliers');
    }
}
