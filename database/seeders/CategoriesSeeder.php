<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories;
class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics','description' =>'phones, laptops, TVs','slug' => 'electronics'],
            ['name' => 'Fashion','description' => 'clothing, shoes, bags','slug' => 'fashion'],
            ['name' => 'Home & Kitchen', 'description' => 'furniture and appliances','slug' => 'home-kitchen'],
            ['name' => 'Books','description' => 'fiction and non-fiction books',   'slug' => 'books'],
            ['name' => 'Sports','description'=>'sports equipment and activewear', 'slug' => 'sports'],
        ];

        foreach ($categories as $data) {
            Categories::firstOrCreate(['slug' => $data['slug']], $data + ['status' => true]);
        }
    }
}