<?php

namespace Database\Factories;

use App\Models\wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Products; 
use App\Models\User;
/**
 * @extends Factory<wishlist>
 */
class WishlistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  protected $model = wishlist::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'product_id' => Products::factory(),
        ];
    }
}