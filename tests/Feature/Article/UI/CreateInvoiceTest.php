<?php

namespace Tests\Feature\Article\UI;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateInvoiceTest extends TestCase
{

    use WithFaker;


    /**
     * test create new invoice
     *
     * @return void
     */
    public function testCreateInvoice_ReturnInvoiceInstance()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create([
            'created_by_id' => $user->id
        ]);
        $customers = Customer::factory()->count(1)->create([
            'created_by_id' => $user->id
        ]);


        $articles = [];


        for ($i = 0; $i < 3; $i++) {
            $quantity = $this->faker->numberBetween(5, 666);
            $category = Category::factory()->create([
                'created_by_id' => $user->id
            ]);
            $subcategory = Category::factory()->create([
                'parent_category_id' => $category->id,
                'created_by_id' => $user->id,
            ]);
            $articleCustomers = [];
            foreach ($customers as $customer) {
                $articleCustomers[] = [
                    'customer_id' => $customer['id'],
                    'quantity' => $quantity,
                    'sales_price' => $this->faker->numberBetween(59, 66666),
                    'comment' => $this->faker->sentence
                ];
            }
            $articles[] = [
                'name' => $this->faker->name,
                'category_id' => $category->id,
                'cost_price' => $this->faker->numberBetween(5, 5655),
                'currency_code' => '$',
                'subcategory_id' => $subcategory->id,
                'quantity' => $quantity,
                'article_identities' => $articleCustomers
            ];
        }

        $this->actingAs($user);
        $response = $this->postJson('/api/purchases', [
            'internal_id' => $this->faker->numberBetween(500, 5000), /// match = id
            'issue_date' => $this->faker->date(), /// match = id
            'due_date' => $this->faker->date(), /// match = id
            'supplier_id' => $supplier->id,
            'articles' => $articles

        ]);


        $this->assertTrue(true); //
    }
}
