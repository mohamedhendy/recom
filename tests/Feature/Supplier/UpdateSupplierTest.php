<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateSupplierTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     *  test  api endpoint accessable after authencitaion
     *
     * @return void

     */
    public function ApiShouldReturnUnauthenticatedStatusCode()
    {
        $supplier = Supplier::factory()->create();
        $this->patchJson("/api/suppliers/{$supplier->id}")->assertStatus(401);
    }



    /**
     *
     * @return void
     * @test
     */
    public function PageShouldRedirectToLoginPage()
    {

        $supplier = Supplier::factory()->create();
        $this->get("/suppliers/{$supplier->id}/edit")->assertRedirect('/login');

    }




    /**

     *
     */
    public function ApiShouldReturnValidationExecptionStatusCode()
    {
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $this->actingAs($user)->patchJson("/api/suppliers/{$supplier->id}")->assertStatus(422);

    }



    /**

     *
     */
    public function ApiShouldRedirectToSuppliersListAfterUpdatingSupplier()
    {
        $user = User::factory()->create();
        $count = 25;
        Supplier::factory()->count(rand(1, $count))->create([
            'created_by_id' => $user->id
        ]);
        $user = User::factory()->create();
        $supplier = Supplier::factory()->create();
        $this->actingAs($user)->patchJson("/api/suppliers/{$supplier->id}",[
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


        $supplier = Supplier::factory()->create();



        $this->actingAs($user)->get("/suppliers/{$supplier->id}/edit")
            ->assertOk();
    }
}
