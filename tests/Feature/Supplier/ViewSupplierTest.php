<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewSupplierTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
  

    /**
     *
     * @return void
     * @test
     */
    public function PageShouldRedirectToLoginPage()
    {

        $supplier = Supplier::factory()->create();
        $this->get("/suppliers/{$supplier->id}")->assertRedirect('/login');

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



        $this->actingAs($user)->get("/suppliers/{$supplier->id}")
            ->assertOk();
    }
}
