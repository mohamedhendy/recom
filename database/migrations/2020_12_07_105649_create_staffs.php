<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('staff_number', 10);
            $table->string('salutation');
            $table->string('name');
            $table->string('first_name');

            //
            $table->string('address');
            $table->string('zip_code', 12);
            $table->string('city');
            $table->string('gender', 20);
            $table->string('nationality');
            $table->date('date_of_birth')->nullable();
            $table->string('birth_city');
            $table->string('birth_country');
            $table->boolean('disability');
            $table->string('disability_degree')->nullable();
            $table->string('tax_id');
            $table->string('tax_class')->nullable();
            $table->string('child_allowances')->nullable();
            $table->string('religion')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('health_insurance')->nullable();
            $table->boolean('parent');
            $table->string('time_model', 21)->nullable();
            $table->float('weekly_working_time');
            $table->float('daily_working_time');
            $table->string('salary_type', 20)->nullable();
            $table->float('hourly_rate');
            $table->float('monthly_rate');

            // Contact details
            $table->string('contact_phone1')->nullable();
            $table->string('contact_phone2')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_homepage')->nullable();

            // Bank details
            $table->string('bank_name')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_bic')->nullable();

            // Work timing details
            $table->time('working_time_monday_from')->nullable();
            $table->time('working_time_monday_to')->nullable();
            $table->time('working_time_tuesday_from')->nullable();
            $table->time('working_time_tuesday_to')->nullable();
            $table->time('working_time_wednesday_from')->nullable();
            $table->time('working_time_wednesday_to')->nullable();
            $table->time('working_time_thursday_from')->nullable();
            $table->time('working_time_thursday_to')->nullable();
            $table->time('working_time_friday_from')->nullable();
            $table->time('working_time_friday_to')->nullable();
            $table->time('working_time_saturday_from')->nullable();
            $table->time('working_time_saturday_to')->nullable();
            $table->time('working_time_sunday_from')->nullable();
            $table->time('working_time_sunday_to')->nullable();
            $table->boolean('legal_break_regulation');
            $table->date('working_start_date')->nullable();
            $table->date('working_end_date')->nullable();

            //
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
        Schema::dropIfExists('staffs');
    }
}
