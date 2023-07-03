<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('number')->nullable();
            $table->string('name')->nullable();
            $table->text('note')->nullable();

            $table->enum('type', ['client', 'stock'])->default('client');
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

            // Delivery Address
            $table->string('delivery_address_salutation')->nullable();
            $table->string('delivery_address_name')->nullable();
            $table->string('delivery_address_first_name')->nullable();
            $table->string('delivery_address_addon1')->nullable();
            $table->string('delivery_address_addon2')->nullable();
            $table->string('delivery_address_street')->nullable();
            $table->string('delivery_address_zip_code', 20)->nullable();
            $table->string('delivery_address_city')->nullable();
            $table->string('delivery_address_country')->nullable();

            // Contact details
            $table->string('contact_phone1')->nullable();
            $table->string('contact_phone2')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_homepage')->nullable();

            $table->unsignedBigInteger('easy_bill_id')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
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
        Schema::dropIfExists('customers');
    }
}
