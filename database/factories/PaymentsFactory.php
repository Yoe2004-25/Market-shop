<?php

namespace Database\Factories;

use App\Models\Payments;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Orders; 
/**
 * @extends Factory<Payments>
 */
class PaymentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'order_id'=> Orders::factory(),
            'payment_method'=> $this->faker->randomElement(['cash', 'visa']),
            'transaction_id'=> 'TXN-' . strtoupper($this->faker->unique()->bothify('????####????')),
            'status'=> $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'amount'=> $this->faker->randomFloat(2, 50, 2000),
        ];
    }
}