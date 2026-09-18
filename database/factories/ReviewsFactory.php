<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewsFactory extends Factory
{
    protected $model = Reviews::class;

    public function definition(): array
    {
        return [
            'user_id'=> User::factory(),
            'product_id'=> Products::factory(),
            'rating'=> $this->faker->numberBetween(1, 5),
            'comment'=> $this->faker->paragraph(),
        ];
    }

    public function positive(): static
    {
        return $this->state(fn () => ['rating' => $this->faker->numberBetween(4, 5)]);
    }

    public function negative(): static
    {
        return $this->state(fn () => ['rating' => $this->faker->numberBetween(1, 2)]);
    }
}