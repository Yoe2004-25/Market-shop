<?php

namespace Database\Factories;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
/**
 * @extends Factory<Orders>
 */
class OrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   

    protected $model=Orders::class;

    public function definition(): array
    {
        $total=$this->faker->randomFloat(2, 50, 2000);
        $shipping=20;
        $tax=$total*0.14;

        return [
            'user_id'=> User::factory(),
            'coupon_id'=> null,
            'name'=>'name of --> order'.$this->faker->unique()->numberBetween(1, 99999),
            'status'=> 'pending',
            'payment_status' => 'pending',
            'total'=> $total,
            'shipping_cost'=> $shipping,
            'tax'=>round($tax, 2),
            'grand_total'=>round($total + $shipping + $tax, 2),
        ];
    }

    public function successful(): static
    {
        return $this->state(fn () => [
            'status'=>'success',
            'payment_status'=>'success',
        ]);
    }
}