<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddMainWarehouse extends Migration
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

        DB::table('warehouses')->insert([
            'name' => 'Main warehouse',
            'description' => "Main Stock Warehouse",
            'created_by_id' => $user->id
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('warehouses')
            ->where('name', 'Main warehouse')
            ->delete();
    }
}
