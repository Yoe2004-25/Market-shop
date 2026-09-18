<?php

namespace Database\Factories;

use App\Models\Coupons;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupons>
 */
class CouponsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
        protected $model = Coupons::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'code' => strtoupper($this->faker->unique()->bothify('????####')),
            'type' => $this->faker->randomElement(['fixed', 'percentage']),
            'value'=> $this->faker->numberBetween(5, 50),
            'expire_date'=> $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'usage_limit'=> $this->faker->numberBetween(1, 100),
            'status'=> 'active',
        ];
    }
}