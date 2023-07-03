<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecreateMissingArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // // get old db connection

        // // 'recom_backup_1' => '2020-12-01', 'recom_backup_2' => '2020-12-01', 'recom_backup_3' => '2020-12-01',
        // $databases = ['recom_backup_4' => '2020-12-01'];

        // foreach ($databases as $database => $date) {
        //     DB::disconnect();
        //     $createdAt = Carbon::parse($date);
        //     Config::set('database.connections.old_pgsql.database', $database);
        //     $oldDbConnection = DB::connection('old_pgsql');
        //     $this->clone($createdAt, $oldDbConnection);
        // }
    }


    public function clone($createdAt, $oldDbConnection)
    {
        // render purchases for the old database and create new purchase => inside loop
        $purchases = $oldDbConnection->table('purchases')->where('created_at', '>', $createdAt)->get();
        foreach ($purchases as $purchaseKey => $purchase) {
            $purchaseData = collect($purchase)->toArray();

            unset($purchaseData['id']);
            // dd(collect($purchaseData)->toArray());
            $newPurchaseId = DB::table('purchases')->insertGetId($purchaseData);

            // dd(  $newPurchase );
            // use the old purchase to render the old articles and create articles => nested loop (1)
            $articles = $oldDbConnection->table('articles')->where([
                ['created_at', '>', $createdAt],
                ['purchase_id', $purchase->id]
            ])->get();

            foreach ($articles as $articleKey => $article) {
                // first
                $articleData = collect($article)->toArray();
                unset($articleData["id"]);
                $articleData["purchase_id"] = $newPurchaseId;
                $newArticleId = DB::table('articles')->insertGetId($articleData);
                // for each article , use the old article id to render the article_identity and create new article identity => nested loop (2)
                $articleIdentities = $oldDbConnection->table('article_identity')->where([
                    ['created_at', '>', $createdAt],
                    ['article_id', $article->id]
                ])->get();

                foreach ($articleIdentities as $articleIdentityKey => $articleIdentity) {
                    $articleIdentityData = collect($articleIdentity)->toArray();
                    unset($articleIdentityData["id"]);

                    $articleIdentityData["article_id"] = $newArticleId;
                    $articleIdentityData["purchase_id"] = $newPurchaseId;

                    // dd($articleIdentityData,$articleData,$purchaseData);
                    $newArticleIdentityId = DB::table('article_identities')->insertGetId($articleIdentityData);
                    // for each article identity we shoud use the old article_identity_id to render old deployments and create new deployments => nested loop (3)
                    $deployments = $oldDbConnection->table('deployments')->where([
                        ['created_at', '>', $createdAt],
                        ['article_identity_id', $articleIdentity->id]
                    ])->get();


                    foreach ($deployments as $deploymentKey => $deployment) {
                        $deployment = collect($deployment)->toArray();
                        unset($deployment["id"]);
                        $deployment["article_identity_id"] = $newArticleIdentityId;
                        DB::table('deployments')->insert($deployment);
                    }
                }


                // create article documents
                $this->cloneDocuments($article, $newArticleId, $oldDbConnection);
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


    public function cloneDocuments($article, $newArticleId, $oldDbConnection)
    {
        $articleDocuments = $oldDbConnection->table('has_documents')->where('document_able_id', $article->id)->get();

        foreach ($articleDocuments as $key => $articleDocument) {
            $articleDocument = collect($articleDocument)->toArray();
            $doc = collect($oldDbConnection->table('documents')->where('id', $articleDocument["document_id"])->first())->toArray();

            unset($doc['id']);
            $newDocumentId = DB::table('documents')->insertGetId(collect($doc)->toArray());

            unset($articleDocument["id"]);


            $articleDocument["document_able_id"] = $newArticleId;
            $articleDocument["document_id"] = $newDocumentId;

            DB::table('has_documents')->insert($articleDocument);
        }
    }
}



/*

    // render purchases for the old database and create new purchase => inside loop
    // use the old purchase to render the old articles and create articles => nested loop (1)
    // for each article , use the old article id to render the article_identity and create new article identity => nested loop (2)
    // for each article identity we shoud use the old article_identity_id to render old deployments and create new deployments => nested loop (3)
*/
