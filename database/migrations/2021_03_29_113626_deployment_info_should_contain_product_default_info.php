<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeploymentInfoShouldContainProductDefaultInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        

        $deployments = DB::table('deployments')->get();


        foreach ($deployments as $deployment) {



            $info = collect(json_decode($deployment->info));
            if($info->has('category_id') && $info->has('id')) 
            {
                $product = DB::table('products')->where('id',$info->get('id'))->first();
                if($product) {
                    DB::table('deployments')->where('id',$deployment->id)->update(['info' => $product->default_info]);
                }
            }

            
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
