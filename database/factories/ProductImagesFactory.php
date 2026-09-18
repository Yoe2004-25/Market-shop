<?php

namespace Database\Factories;

use App\Models\Product_images;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Products;
/**
 * @extends Factory<Product_images>
 */
class ProductImagesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Products::factory(),
            'image'      => 'products/' . $this->faker->uuid() . '.png',
            'primary'    => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => ['primary' => true]);
    }
}