<?php

namespace Database\Factories;

use App\Models\Cart_items;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Cart;
use App\Models\Products ;
/**
 * @extends Factory<Cart_items>
 */
class CartItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
 

    protected $model = Cart_items::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 10, 500);
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'cart_id'=> Cart::factory(),
            'product_id'=> Products::factory(),
            'quantity'=>$quantity,
            'price'=>$price,
        ];
    }
}