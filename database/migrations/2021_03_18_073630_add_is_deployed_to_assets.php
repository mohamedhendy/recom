<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIsDeployedToAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->boolean('is_deployed')->default(false);
        });

        $assets = DB::table('assets')->get();
        foreach ($assets as $asset) {
            $hasDeployment = DB::table('deployments')
                ->where('asset_id', $asset->id)
                ->first();
            $isDeployed = false;
            if ($hasDeployment || $asset->sale_order_product_id) {
                $isDeployed = true;
            }

            DB::table('assets')
                ->where('id', $asset->id)->update([
                    'is_deployed' => $isDeployed
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
        Schema::table('assets', function (Blueprint $table) {
            //
            $table->dropColumn('is_deployed');
        });
    }
}
