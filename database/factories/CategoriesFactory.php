<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriesFactory extends Factory
{
    protected $model = Categories::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name'=> ucfirst($name),
            'slug'=> str($name)->slug(),
            'description'=> $this->faker->sentence(8),
            'image'=> null,
            'status'=> true,
        ];
    }

   
    public function inactive(): static
    {
        return $this->state(fn () => ['status' => false]);
    }

   
    public function withImage(): static
    {
        return $this->state(fn () => [
            'image' => 'categories/' . $this->faker->uuid() . '.png',
        ]);
    }
}