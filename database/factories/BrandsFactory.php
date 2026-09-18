<?php

namespace Database\Factories;

use App\Models\Brands;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandsFactory extends Factory
{
    protected $model = Brands::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'logo' => null, 
        ];
    }

   
    public function withLogo(): static
    {
        return $this->state(fn () => [
            'logo' => 'brands/' . $this->faker->uuid() . '.png',
        ]);
    }
}