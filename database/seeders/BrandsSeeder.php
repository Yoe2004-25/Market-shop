<?php

namespace Database\Seeders;

use App\Models\Brands;
use Illuminate\Database\Seeder;

class BrandsSeeder extends Seeder
{
    public function run(): void
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'New Balance', 'iphone' , 'tablet' , 't-shirt' , 'cap','toy story'];

        foreach ($brands as $name) {
            Brands::create(['name' => $name]);
        }
    }
}