<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->bigInteger('staff_id')->nullable();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('role', 100)->nullable();
            $table->string('auth_phone', 100)->nullable();
            $table->integer('company_id')->nullable();
            $table->boolean('uses2fa')->nullable();
            $table->string('auth_code', 10)->nullable();
            $table->boolean('active')->nullable();
            $table->text('backup_codes')->nullable()->comment('(DC2Type:simple_array)');
            $table->integer('max_idle_time')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('users')->insert([
            'name' => 'dummy',
            'email' => 'dummy',
            'password' => 'dummy',
            'active' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
