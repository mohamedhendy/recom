<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddActualCreationYearForPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->timestamp('creation_year')->nullable();
        });

        $purchases = DB::table('purchases')->get();

        foreach ($purchases as $purchase) {
            DB::table('purchases')
                ->where('id', $purchase->id)
                ->update([
                    'creation_year' => $purchase->created_at
                ]);
        }
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
