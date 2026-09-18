<?php

namespace Database\Factories;

use App\Models\OrdersItems;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User ;
use App\Models\Orders; 
use App\Models\Products; 
/**
 * @extends Factory<OrdersItems>
 */
class OrdersItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   
    protected $model = OrdersItems::class;

    public function definition(): array
    {
        $price    = $this->faker->randomFloat(2, 10, 500);
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'order_id'=> Orders::factory(),
            'product_id'=> Products::factory(),
            'user_id'=> User::factory(),
            'quantity'=> $quantity,
            'price'=> $price,
            'subtotal'=> $price * $quantity,
            'details'=> $this->faker->sentence(),
        ];
    }
}