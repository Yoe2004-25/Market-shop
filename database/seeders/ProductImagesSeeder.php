<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Products;
use App\Models\Product_images; 
class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Products::take(15)->get();
        foreach ($products as $product)
        {
            Product_images::factory()->primary()->create(['product_id' => $product->id]);
            Product_images::factory()->count(2)->create(['product_id' => $product->id]);
        }
    }
}