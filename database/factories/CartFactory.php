<?php

namespace Database\Factories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
/**
 * @extends Factory<Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      return [
            'name'=> $this->faker->unique()->numberBetween(1, 99999),
            'content' => $this->faker->sentence(),
            'user_id'=> User::factory(),
        ];
    }
}