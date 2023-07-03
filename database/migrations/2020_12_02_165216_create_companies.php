<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('legal_form')->nullable();
            $table->string('company')->nullable();
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();

            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('po_zip_code')->nullable();
            $table->string('po_city')->nullable();
            $table->string('po_country')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->string('sepa_creditor_id')->nullable();

            // Contact details
            $table->string('contact_phone1')->nullable();
            $table->string('contact_phone2')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_homepage')->nullable();

            // Bank details
            $table->string('bank1_name')->nullable();
            $table->string('bank1_iban')->nullable();
            $table->string('bank1_bic')->nullable();
            $table->string('bank2_name')->nullable();
            $table->string('bank2_iban')->nullable();
            $table->string('bank2_bic')->nullable();

            // Easy bill
            $table->boolean('use_easy_bill')->default(false);
            $table->text('easy_bill_api_key')->nullable();
            $table->bigInteger('last_purchase_number');
            $table->bigInteger('purchase_year');
            $table->bigInteger('purchase_number_length');


//            //
//            $table->unsignedBigInteger('created_by_id')->nullable();
//            $table->unsignedBigInteger('updated_by_id')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
