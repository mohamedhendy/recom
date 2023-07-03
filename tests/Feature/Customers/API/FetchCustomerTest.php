<?php

namespace Tests\Feature\Customers\API;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchCustomerTest extends TestCase
{

    use RefreshDatabase, WithFaker;
    /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShoudReturnUnauthorizedStatusCode_UserNotLogged()
    {
        $response = $this->getJson('/api/customers');
        $response->assertUnauthorized();
    }




    /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShoudReturnStatusCodeOk_RouteExists()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('/api/customers');
        $response->assertOk();
    }


    /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldReturnJsonResponse()
    {
        $itemsCount = $this->faker->numberBetween(20, 100);
        $user = User::factory()->create();
        $customers = Customer::factory()->count($itemsCount)->create();
        $response = $this->actingAs($user)->getJson('/api/customers');
        $response
            ->assertJson([
                'data' => [],
            ])
            ->assertJsonPath('meta.current_page', 1)
            ->assertJsonPath('meta.per_page', 100)
            ->assertJsonPath("meta.last_page", 1)
            ->assertOk();
    }


    /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldReturnListWithSpecificPagination()
    {
        $page = $this->faker->numberBetween(2, 5);
        $perPage = $this->faker->numberBetween(20, 59);
        $itemsCount = $this->faker->numberBetween(250, 600);
        $user = User::factory()->create();
        Customer::factory()->count($itemsCount)->create([
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id
        ]);
        $response = $this->actingAs($user)->getJson('/api/customers?per_page=' . $perPage . '&&page=' . $page);
        $response
            ->assertJson([
                'data' => [],
            ])
            ->assertJsonPath('meta.current_page', $page)
            ->assertJsonPath('meta.per_page', "$perPage")
            ->assertOk();
    }


    /**
     *  test api return invalid logged user
     *
     * @return void
     * @test
     */
    public function itShouldReturnListWithExepectedObject()
    {
        $page = $this->faker->numberBetween(2, 5);
        $perPage = $this->faker->numberBetween(20, 59);
        $itemsCount = $this->faker->numberBetween(250, 600);
        $user = User::factory()->create();
        Customer::factory()->count($itemsCount)->state([
            'created_by_id' => $user->id,
            'updated_by_id' => $user->id
        ])->create();
        $response = $this->actingAs($user)->getJson('/api/customers?per_page=' . $perPage . '&&page=' . $page);
        $response

            ->assertJsonPath('meta.current_page', $page)
            ->assertJsonPath('meta.per_page', "$perPage")
            ->assertJsonStructure([
                'data' => [
                    [
                        "id",
                        "number",
                        "name",
                        "created_by" => [
                            "id",
                            "name"
                        ],
                        "updated_by" => [
                            "id",
                            "name"
                        ],
                        "created_at"
                    ]
                ]
            ])
            ->assertOk();
    }
}
