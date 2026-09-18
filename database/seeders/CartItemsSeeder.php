<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cart;
use App\Models\User;

class CartItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $users = User::take(10)->get();
        foreach ($users as $user) 
        {
            Cart::firstOrCreate(['user_id' => $user->id],['name'    => 'Cart for ' . $user->name,'content' => '',]);
        }
    }
}