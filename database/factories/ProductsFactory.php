<?php

namespace Database\Factories;

use App\Models\Brands;
use App\Models\Categories;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductsFactory extends Factory
{
    protected $model = Products::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 20, 1000);

        return [
            'category_id'=> Categories::factory(),
            'brand_id'=> Brands::factory(),
            'name'=> ucfirst($name),
            'slug'=> str($name)->slug() . '-' . $this->faker->unique()->numberBetween(1, 99999),
            'description' => $this->faker->paragraph(),
            'price'=> $price,
            'discount'=> 0,
            'stock'=> $this->faker->numberBetween(1, 100),
            'sku'=> 'SKU-' . strtoupper($this->faker->unique()->bothify('????####')),
            'image' => null,
            'status'=> 'active',
        ];
    }
}