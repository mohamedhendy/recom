<?php

namespace Tests\Feature\Customers\API;

use App\Http\Middleware\VerifyCsrfToken;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class UpdateCustomerTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    private $apiUrl = '/api/customers/?';

    private function getUrl($id)
    {
        return Str::replaceArray('?', [$id], $this->apiUrl);
    }
      /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldReturnUnauthenticatedStatusCode()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $customer = Customer::factory()->create();
        $this->patchJson($this->getUrl($customer->id))->assertStatus(401);
    }


      /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldReturnValidationExecptionStatusCode()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $customer = Customer::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user)->patchJson($this->getUrl($customer->id))->assertStatus(422);
    }



      /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldUpdateCustomerDataAndRedirect()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $user = User::factory()->create();
        $user = User::factory()->create();
        $customer = Customer::factory()->create();
        $this->actingAs($user)->patchJson($this->getUrl($customer->id), [
            'name' => $this->faker->userName,
            'number' => $this->faker->numberBetween(5, 55),
            'type' => $this->faker->randomElement(['client', 'stock']),
            "address_salutation" => $this->faker->secondaryAddress,
            "address_name" => $this->faker->address,
            "address_street" => $this->faker->streetAddress,
            "address_zip_code" => $this->faker->numberBetween(1000, 8898),
            "address_city" =>  $this->faker->city,
            "address_country" =>  $this->faker->country,
            "contact_phone1" =>  $this->faker->phoneNumber,
            "contact_mobile" => $this->faker->phoneNumber,
            "contact_email" => $this->faker->companyEmail,


        ])->assertRedirect('/customers');
    }
}
