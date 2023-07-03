<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedOldProducts extends Migration
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

        $articles = DB::table('articles')->get();
        foreach ($articles as $article) {
            $isCreated = DB::table('products')->where([
                ['name', $article->name],
                ['category_id', $article->subcategory_id]
            ])->first();

            if (!$isCreated) {
                DB::table('products')->insert([
                    'name' => $article->name,
                    'category_id' => $article->subcategory_id,
                    'created_by_id' => $user->id
                ]);
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
        DB::table('products')->truncate();
    }
}
