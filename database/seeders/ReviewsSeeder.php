<?php

namespace Database\Seeders;

use App\Models\Products;
use App\Models\Reviews;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(20)->get();
        $products = Products::take(10)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Please seed users and products first.');
            return;
        }

        foreach ($products as $product) {
            foreach ($users->random(rand(2, 5)) as $user) {Reviews::firstOrCreate([
                        'user_id'    => $user->id,
                        'product_id' => $product->id,],['rating'  => rand(1, 5),'comment' => fake()->paragraph(),]);
            }
        }
    }
}