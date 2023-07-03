<?php

namespace Tests\Feature\Supplier;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateSupplierTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     *  test  api endpoint accessable after authencitaion
     *
     * @return void

     */
    public function ApiShouldReturnUnauthenticatedStatusCode()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $this->postJson('/api/suppliers')->assertStatus(401);
    }



    /**
     *
     * @return void
     * @test
     */
    public function PageShouldRedirectToLoginPage()
    {

        $this->get('/suppliers/create')->assertRedirect('/login');
    }




    /**

     *
     */
    public function ApiShouldReturnValidationExecptionStatusCode()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = User::factory()->create();
        $this->actingAs($user)->postJson('/api/suppliers')->assertStatus(422);
    }



    /**

     *
     */
    public function ApiShouldRedirectToSuppliersListAfterCreatingSupplier()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = User::factory()->create();
        $count = 25;
        Supplier::factory()->count(rand(1, $count))->create([
            'created_by_id' => $user->id
        ]);
        $this->actingAs($user)->postJson('/api/suppliers',[
            'name' => $this->faker->userName,
            'number' => $this->faker->numberBetween(5,55),
            'tax_id' => $this->faker->numberBetween(5,55),
            "vat_id" => $this->faker->numberBetween(5,55),
            "address_salutation" => $this->faker->secondaryAddress,
            "address_name" => $this->faker->address,
            "address_street" => $this->faker->streetAddress,
            "address_zip_code" => $this->faker->numberBetween(1000,8898),
            "address_city" =>  $this->faker->city,
            "address_country" =>  $this->faker->country,
            "contact_details1_phone1" =>  $this->faker->phoneNumber,
            "contact_details1_mobile" => $this->faker->phoneNumber,
            "contact_details1_email" => $this->faker->companyEmail,
            "bank_name" => $this->faker->bankAccountNumber,
            "bank_bic" => $this->faker->bankAccountNumber,
            "bank_iban" => $this->faker->iban,

        ])->assertRedirect('/suppliers');

    }

    /**
     * @test
     *
     */
    public function PageShouldDisplayCreateSupplierPage()
    {
        $user = User::factory()->create();
        $count = 25;
        Supplier::factory()->count(rand(1, $count))->create([
            'created_by_id' => $user->id
        ]);
        $this->actingAs($user)->get('/suppliers/create')
            ->assertOk();
    }
}
