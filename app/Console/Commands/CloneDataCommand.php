<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleIdentity;
use App\Models\Category;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Staff;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CloneDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cloneData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    private $stockCustomer;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {


        try {
            $this->call('migrate:fresh');
            $oldDbConnection = DB::connection('old_pgsql');
            $this->cloneCompanies($oldDbConnection);
            $this->cloneUsers($oldDbConnection);
            $this->cloneCategories($oldDbConnection);

            $this->cloneCustomers($oldDbConnection);
            $this->cloneStaffs($oldDbConnection);
            $this->cloneSuppliers($oldDbConnection);
            $this->clonePurchases($oldDbConnection);
            $this->cloneIdentitiesDeployments($oldDbConnection);

            Article::where('id', '>', 0)->update([
                'documents_upload' => true
            ]);
            $articlesIdnetities = ArticleIdentity::all();
            foreach ($articlesIdnetities as $artcieIdentity) {
                $artcieIdentity->update([
                    'supplier_id' => $artcieIdentity->article->purchase->supplier_id
                ]);
            }

            DB::commit();
        } catch (QueryException $queryException) {
            DB::rollBack();
            throw $queryException;
        }

        return 0;
    }

    public function cloneCompanies($oldConnection)
    {
        $oldCompanies = $oldConnection->table('companies')->orderBy('id', 'asc')->orderBy('id', 'asc')->get()->toArray();

        foreach ($oldCompanies as $raw) {

            $oldCompany = collect($raw)->toArray();

            unset($oldCompany["managing_director"]);
            unset($oldCompany["addon2"]);
            unset($oldCompany["addon1"]);
            unset($oldCompany["supervisory_board"]);
            unset($oldCompany["registry_number"]);
            unset($oldCompany["country"]);
            unset($oldCompany["po_address"]);
            unset($oldCompany["license_valid_until"]);
            unset($oldCompany["registry_court"]);
            unset($oldCompany["board_members"]);
            unset($oldCompany["created_by_id"]);
            unset($oldCompany["updated_by_id"]);
            //            unset($dbData["id"]);
            $company = new Company($oldCompany);
            $company->save();
            DB::statement("SELECT setval(pg_get_serial_sequence('companies', 'id'), coalesce(max(id)+1, 1), false) FROM companies");
        }
    }

    public function cloneUsers($oldConnection)
    {
        $oldUsers = $oldConnection->table('users')->orderBy('id', 'asc')->get()->toArray();


        foreach ($oldUsers as $raw) {

            $oldUser = collect($raw)->toArray();

            $oldUser['email'] = strtolower($oldUser['email']);
            unset($oldUser["managing_director"]);
            unset($oldUser["addon2"]);
            unset($oldUser["addon1"]);
            unset($oldUser["supervisory_board"]);
            unset($oldUser["registry_number"]);
            unset($oldUser["country"]);
            unset($oldUser["po_address"]);
            unset($oldUser["license_valid_until"]);
            unset($oldUser["registry_court"]);
            unset($oldUser["board_members"]);
            unset($oldUser["created_by_id"]);
            unset($oldUser["updated_by_id"]);
            unset($oldUser["updated_by_id"]);


            User::create($oldUser);

            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), coalesce(max(id)+1, 1), false) FROM users");
        }

        //

    }

    public function cloneCategories($oldConnection)
    {
        $oldCategories = $oldConnection->table('purchase_types')->orderBy('id', 'asc')->get()->toArray();

        foreach ($oldCategories as $raw) {

            $oldCategory = collect($raw)->toArray();
            unset($oldCategory["id"]);
            $category = Category::create($oldCategory);

            $oldCategorySubDatas = $oldConnection->table('tags')->where('type', $raw->id)->orderBy('id', 'asc')->get()->toArray();
            foreach ($oldCategorySubDatas as $subRaw) {

                $oldCategorySubData = collect($subRaw)->toArray();
                $oldCategorySubData['parent_category_id'] = $category->id;
                $oldCategorySubData['payload'] = $subRaw->id;
                unset($oldCategorySubData["type"]);
                unset($oldCategorySubData["id"]);
                Category::create($oldCategorySubData);
            }
        }

        //
    }

    public function cloneCustomers($oldConnection)
    {
        $oldCustomers = $oldConnection->table('customers')->orderBy('id', 'asc')->get()->toArray();

        foreach ($oldCustomers as $raw) {

            $oldCustomer = collect($raw)->toArray();
            $oldCustomer['number'] = $oldCustomer['customer_number'];
            $oldCustomer['name'] = $oldCustomer['customer_name'];
            unset($oldCustomer["customer_number"]);
            unset($oldCustomer["customer_name"]);
            ///1,2,4,5,7
            Customer::create($oldCustomer);
        }
        DB::statement("SELECT setval(pg_get_serial_sequence('customers', 'id'), coalesce(max(id)+1, 1), false) FROM customers");

        $this->stockCustomer = Customer::factory()->create([
            'created_by_id' => 1,
            'updated_by_id' => 1,
            'number' => '99999999999',
            'name' => 'Lager / Stock',
            'type' => 'stock'

        ]);
    }

    public function cloneStaffs($oldConnection)
    {
        $oldStaffs = $oldConnection->table('staffs')->orderBy('id', 'asc')->get()->toArray();

        foreach ($oldStaffs as $raw) {

            $oldStaff = collect($raw)->toArray();
            Staff::create($oldStaff);
        }
        DB::statement("SELECT setval(pg_get_serial_sequence('staffs', 'id'), coalesce(max(id)+1, 1), false) FROM staffs");
    }

    public function cloneSuppliers($oldConnection)
    {
        $oldSuppliers = $oldConnection->table('suppliers')->orderBy('id', 'asc')->get()->toArray();

        foreach ($oldSuppliers as $raw) {

            $oldSupplier = collect($raw)->toArray();
            $oldSupplier['number'] = $oldSupplier['supplier_number'];
            $oldSupplier['name'] = $oldSupplier['supplier_name'];
            $oldSupplier['description'] = $oldSupplier['note'];
            unset($oldSupplier["supplier_number"]);
            unset($oldSupplier["supplier_name"]);
            unset($oldSupplier["note"]);
            //            unset($dbData["id"]);
            Supplier::create($oldSupplier);
        }
        DB::statement("SELECT setval(pg_get_serial_sequence('suppliers', 'id'), coalesce(max(id)+1, 1), false) FROM suppliers");
    }


    public function clonePurchases($oldConnection)
    {
        $oldPurchases = $oldConnection->table('purchases')->orderBy('id', 'asc')->get();

        foreach ($oldPurchases as $oldPurchase) {

            $newCategory = Category::where('payload', $oldPurchase->tag_id)->first();
            $oldStaffArticles = $oldConnection->table('staff_purchases')->where('purchase_id', $oldPurchase->id)->orderBy('id', 'asc')->get();

            $newPurchase = new Invoice([
                'id' => $oldPurchase->id,
                'internal_id' => $oldPurchase->internal_purchase_number,
                'number' => $oldPurchase->purchase_number,
                'supplier_id' => $oldPurchase->supplier_id,
                'issue_date' => $oldPurchase->purchase_date,
                'due_date' => Carbon::parse($oldPurchase->purchase_date)->addDays(3),
                'created_at' => $oldPurchase->created_at,
                'deleted_at' => $oldPurchase->deleted_at,
                'updated_at' => $oldPurchase->updated_at,
                'created_by_id' => $oldPurchase->created_by_id,
                'updated_by_id' => $oldPurchase->updated_by_id,
            ]);

            $newPurchase->save();

            /* @var Article $newArticle */
            $newArticle = $newPurchase->articles()->create([
                'name' => $oldPurchase->article,
                'quantity' => $oldPurchase->amount ? $oldPurchase->amount : 0,
                'cost_price' => $oldPurchase->price_per_piece_amount,
                'currency_code' => $oldPurchase->price_per_piece_currency_code,
                'created_by_id' => $oldPurchase->created_by_id,
                'updated_by_id' => $oldPurchase->updated_by_id,
                'category_id' => $newCategory->parent_category_id ? $newCategory->parent_category_id : $newCategory->id,
                'subcategory_id' => $newCategory->id,
                'created_at' => $oldPurchase->created_at,
                'updated_at' => $oldPurchase->updated_at,
            ]);

            foreach ($oldStaffArticles as $oldStaffArticle) {
                /* @var Staff $newStaff */
                $newStaff = Staff::find($oldStaffArticle->staff_id);
                /* @var ArticleIdentity $newArticleStaff */
                $newArticleStaff = $newStaff->articleIdentities()->create([
                    'article_id' => $newArticle->id,
                    'quantity' => $oldStaffArticle->amount,
                    'delivered_quantity' => $oldStaffArticle->amount_delivered,
                    'billed_quantity' => 0,
                    'created_by_id' => $oldStaffArticle->created_by_id,
                    'description' => $oldStaffArticle->description,
                    'created_at' => $oldStaffArticle->created_at,
                    'updated_at' => $oldStaffArticle->updated_at,
                    'updated_by_id' => $oldStaffArticle->updated_by_id
                ]);
                // $oldProducts = $oldConnection->table('products')->where([['staff_id', $oldStaffArticle->staff_id], ['purchase_id', $oldPurchase->id]])->orderBy('id', 'asc')->get();
                $oldProducts = $oldConnection->table('products')->where('purchase_staff_id', $oldStaffArticle->id)->orderBy('id', 'asc')->get();
                foreach ($oldProducts as $oldProduct) {
                    $newArticleStaff->deployments()->create([
                        'supplier_id' => $newPurchase->supplier_id,
                        'billed_by_id' => $oldProduct->billed_by_id,
                        'billed' => $oldProduct->billed,
                        'billed_at' => $oldProduct->billed_at,
                        'a_number' => $oldProduct->a_number,
                        'o_number' => $oldProduct->o_number,
                        'serial_number' => $oldProduct->serial_number,
                        'type' => $oldProduct->type,
                        'name' => $oldProduct->name,
                        'mac_address' => $oldProduct->mac_address,
                        'license_key' => $oldProduct->license_key,
                        'storage_location' => $oldProduct->storage_location,
                        'created_by_id' => $oldProduct->created_by_id,
                        'updated_by_id' => $oldProduct->updated_by_id,
                        'created_at' => $oldProduct->created_at,
                        'updated_at' => $oldProduct->updated_at,
                    ]);
                }

                $quantity = $oldProducts->count() > $oldStaffArticle->amount ? $oldProducts->count() : $oldStaffArticle->amount;

                $newArticleStaff->update([
                    'quantity' => $quantity,
                    'delivered_quantity' => $quantity,
                    'billed_quantity' => $quantity,
                ]);
            }

            $oldCustomers = $oldConnection->table('customer_purchases')->where('purchase_id', $oldPurchase->id)->orderBy('created_at', 'asc')->get();
            foreach ($oldCustomers as $oldCustomer) {

                /* @var Customer $newCustomer */
                $newCustomer = Customer::find($oldCustomer->customer_id);

                /* @var ArticleIdentity $newArticleCustomer */
                $newArticleCustomer = $newCustomer->articleIdentities()->create([
                    'article_id' => $newArticle->id,
                    'quantity' => $oldCustomer->amount,
                    'sales_price' => $oldCustomer->sale_price,
                    'project_id' => $oldCustomer->project_id,
                    'delivered_quantity' => $oldCustomer->amount_delivered,
                    'billed_quantity' => 0,
                    'created_by_id' => $oldCustomer->created_by_id,
                    'description' => $oldCustomer->description,
                    'created_at' => $oldCustomer->created_at,
                    'updated_at' => $oldCustomer->updated_at,
                    'updated_by_id' => $oldCustomer->updated_by_id
                ]);
                $oldProducts = $oldConnection->table('products')->where('purchase_customer_id', $oldCustomer->id)->orderBy('id', 'asc')->get();
                $newDeliveredQuantityPerCustomer = 0;
                foreach ($oldProducts as $oldProduct) {
                    $newArticleCustomer->deployments()->create([
                        'supplier_id' => $newPurchase->supplier_id,
                        'billed_by_id' => $oldProduct->billed_by_id,
                        'billed' => $oldProduct->billed,
                        'billed_at' => $oldProduct->billed_at,
                        'a_number' => $oldProduct->a_number,
                        'o_number' => $oldProduct->o_number,
                        'serial_number' => $oldProduct->serial_number,
                        'type' => $oldProduct->type,
                        'name' => $oldProduct->name,
                        'mac_address' => $oldProduct->mac_address,
                        'license_key' => $oldProduct->license_key,
                        'storage_location' => $oldProduct->storage_location,
                        'created_by_id' => $oldProduct->created_by_id,
                        'updated_by_id' => $oldProduct->updated_by_id,
                        'created_at' => $oldProduct->created_at,
                        'updated_at' => $oldProduct->updated_at,
                    ]);

                    if ($oldProduct->billed)
                        $newDeliveredQuantityPerCustomer += 1;
                }


                $quantity = $oldProducts->count() > $oldCustomer->amount ? $oldProducts->count() : $oldCustomer->amount;

                $newArticleCustomer->update([
                    'quantity' => $quantity,
                    'delivered_quantity' => $quantity,
                    'billed_quantity' => $quantity,
                ]);
            }


            if ($oldPurchase->stock_amount > 0) {
                $this->stockCustomer->articleIdentities()->create([
                    'article_id' => $newArticle->id,
                    'quantity' => $oldPurchase->stock_amount,
                    'sales_price' => 0,
                    'project_id' => null,
                    'delivered_quantity' => $oldPurchase->stock_amount,
                    'billed_quantity' => $oldPurchase->stock_amount,
                    'created_by_id' => $oldPurchase->created_by_id,
                    //                    'description' => $oldPurchase->description,
                    'created_at' => $oldPurchase->created_at,
                    'updated_at' => $oldPurchase->updated_at,
                    'updated_by_id' => $oldPurchase->updated_by_id
                ]);
            }


            $oldDocumentsPurchases = $oldConnection->table('document_purchase')->where('purchase_id', $oldPurchase->id)->get();
            foreach ($oldDocumentsPurchases as $oldDocumentsPurchase) {
                $oldDocument = $oldConnection->table('documents')->where('id', $oldDocumentsPurchase->document_id)->first();

                $newDocument = Document::create([
                    'name' => $oldDocument->name,
                    'original_name' => $oldDocument->original_name,
                    'mime_type' => $oldDocument->mime_type,
                    'size' => $oldDocument->size,
                    'path' => $oldDocument->path . '/' . $oldDocument->name,
                    'description' => $oldDocument->description,
                    'created_at' => $oldDocument->created_at,
                    'updated_at' => $oldDocument->updated_at,
                    'updated_by_id' => $oldDocument->updated_by_id,
                    'created_by_id' => $oldDocument->created_by_id,
                ]);

                $newArticle->documents()->create([
                    'document_id' => $newDocument->id,
                    'updated_by_id' => $oldDocument->updated_by_id,
                    'created_by_id' => $oldDocument->created_by_id,
                    'created_at' => $oldDocument->created_at,
                    'updated_at' => $oldDocument->updated_at,
                ]);
            }
        }

        DB::statement("SELECT setval(pg_get_serial_sequence('purchases', 'id'), coalesce(max(id)+1, 1), false) FROM purchases");
    }


    public function cloneIdentitiesDeployments($oldConnection)
    {
        $oldProducts = $oldConnection->table('products')->where([
            ['purchase_customer_id', null],
            ['purchase_staff_id', null],
        ])->orderBy('id', 'asc')->get();

        foreach ($oldProducts as $oldProduct) {
            $identity = false;

            if ($oldProduct->customer_id) {
                $identity = Customer::find($oldProduct->customer_id);
            }

            if ($oldProduct->staff_id) {
                $identity = Staff::find($oldProduct->staff_id);
            }


            if ($identity) {
                $identity->deployments()->create([

                    'billed_by_id' => $oldProduct->billed_by_id,
                    'billed' => $oldProduct->billed,
                    'billed_at' => $oldProduct->billed_at,
                    'a_number' => $oldProduct->a_number,
                    'o_number' => $oldProduct->o_number,
                    'serial_number' => $oldProduct->serial_number,
                    'type' => $oldProduct->type,
                    'name' => $oldProduct->name,
                    'mac_address' => $oldProduct->mac_address,
                    'license_key' => $oldProduct->license_key,
                    'storage_location' => $oldProduct->storage_location,
                    'created_by_id' => $oldProduct->created_by_id,
                    'updated_by_id' => $oldProduct->updated_by_id,
                    'created_at' => $oldProduct->created_at,
                    'updated_at' => $oldProduct->updated_at,
                ]);
            }
        }
    }
}
