<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TeamviewerInitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teamviewer_connections', function (Blueprint $table) {
            $table->string('tvc_id');                                     // uuid NOT NULL,
            $table->text('tvc_notes')->nullable();                        // text,
            $table->text('tvc_user_id');                                  // text NOT NULL,
            $table->text('tvc_group_id')->nullable();                     // text,
            $table->text('tvc_device_id');                                // text NOT NULL,
            $table->text('tvc_user_name');                                // text NOT NULL,
            $table->text('tvc_group_name')->nullable();                   // text,
            $table->text('tvc_device_name')->nullable();                  // text,
            $table->text('tvc_contact_id')->nullable();                   // text,
            $table->double('tvc_fee')->default(0);                  // double precision DEFAULT 0 NOT NULL,
            $table->text('tvc_currency')->default('EURO');          // text DEFAULT 'Euro'::text NOT NULL,
            $table->text('tvc_billing_state');                            // text NOT NULL,
            $table->dateTimeTz('tvc_start_date');                         // timestamp with time zone NOT NULL,
            $table->dateTimeTz('tvc_end_date')->nullable();               // timestamp with time zone,
            $table->integer('rec_id')->nullable();                        // integer,
            $table->dateTimeTz('tvc_created_at')->default('NOW()'); // timestamp with time zone DEFAULT now() NOT NULL,
            $table->dateTimeTz('tvc_updated_at')->default('NOW()'); // timestamp with time zone DEFAULT now() NOT NULL,
            $table->integer('tvc_created_by');                            // integer NOT NULL,
            $table->integer('tvc_updated_by');                            // integer NOT NULL,
            $table->integer('tvc_updated_revision')->default(0);     // integer DEFAULT 0 NOT NULL,
            $table->string('tvc_duration')->default('00:00:00');     // interval DEFAULT '00:00:00'::interval NOT NULL,
            $table->text('tvc_status')->default('fresh');            // text DEFAULT 'fresh'::text NOT NULL,
            $table->boolean('tvc_ignored')->default(false);          // boolean DEFAULT false NOT NULL,
            $table->text('tvc_internal_comment')->nullable();             // text,
            $table->text('tvc_outlook_entry_id')->nullable();             // text,
            $table->bigIncrements('tvc_seq');                             // bigint NOT NULL,
            $table->text('tvc_type')->default('tvc');               // text DEFAULT 'tvc'::text NOT NULL,
            $table->integer('cu_id')->nullable();                         // integer,
            $table->integer('tvc_ae')->default(0);                  // integer DEFAULT 0 NOT NULL,
            $table->integer('ae_mode_id')->default(1);              // integer DEFAULT 1 NOT NULL,
            $table->jsonb('tvc_extra_data')->default('{}');         // jsonb DEFAULT '{}'::jsonb NOT NULL
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teamviewer_connections');
    }
}
