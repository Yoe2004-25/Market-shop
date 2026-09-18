<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use App\Models\Products; 
use App\Models\wishlist; 
class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::take(10)->get();
        $products = Products::take(15)->get();
        if ($users->isEmpty() || $products->isEmpty()) 
        {
            $this->command->warn('Seed users and products first.');
            return;
        }
        foreach ($users as $user) {
            foreach ($products->random(rand(2, 5)) as $product) {
                wishlist::firstOrCreate([  'user_id'=> $user->id, 'product_id' => $product->id, ]);
            }
        }
    }
}