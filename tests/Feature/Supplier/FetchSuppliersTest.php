<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FetchSuppliersTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     *  test  api endpoint accessable after authencitaion
     *
     * @return void
     * @test
     */
    public function ApiShouldReturnUnauthenticatedStatusCode()
    {
        $this->getJson('/api/suppliers')->assertStatus(401);
    }



    /**
     *  test page endpoint accessable after authencitaion
     *
     * @return void
     * @test
     */
    public function PageShouldRedirectToLoginPage()
    {

        $this->get('/suppliers')->assertRedirect('/login');
    }




    /**
     * test api should return empty suppliers list
     * @test
     *
     */
    public function ApiShouldReturnEmptyList()
    {
        $user = User::factory()->create();
        $this->actingAs($user)->getJson('/api/suppliers')->assertOk()->assertJson(['data' => []]);
    }


    //
    /**
     * test api should return empty suppliers list
     * @test
     *
     */
    public function ApiShoudReturnSuppliersList()
    {
        $user = User::factory()->create();
        $count = 25;
        Supplier::factory()->count(rand(1, $count))->create([
            'created_by_id' => $user->id
        ]);
        $this->actingAs($user)->getJson('/api/suppliers')
            ->assertOk()
            // ->assertJson([ null, 'data' => []])
            ->assertJsonPath("meta.current_page",1)
            ->assertJsonPath("meta.per_page",25)
            ->assertJsonPath("links.prev",null);
    }

    /**
     * test page should display suplliers list page
     * @test
     *
     */
    public function PageShouldDisplaySuppliersList()
    {


        $user = User::factory()->create();
        $count = 25;
        Supplier::factory()->count(rand(1, $count))->create([
            'created_by_id' => $user->id
        ]);
        $this->actingAs($user)->get('/suppliers')
            ->assertOk()
;
    }
}
