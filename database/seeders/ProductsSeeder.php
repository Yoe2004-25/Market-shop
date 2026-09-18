<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categories; 
use App\Models\Products;
use App\Models\Brands; 

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
    public function run(): void
    {
        $categories=Categories::all();
        $brands=Brands::all();
        if ($categories->isEmpty() || $brands->isEmpty()) {
            $this->command->warn('Seed categories and brands first.');
            return;
        }
        Products::factory()->count(30)->recycle($categories)
            ->recycle($brands)
            ->create();
    }
}