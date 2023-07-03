<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [

            'number' => $this->faker->numberBetween(3233, 5555),
            'name' => $this->faker->userName,
            'description' => $this->faker->sentence,
            'created_by_id' => User::factory()->create()
        ];
    }
}
