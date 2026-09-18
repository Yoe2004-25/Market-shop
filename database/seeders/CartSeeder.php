<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;  
class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   
    public function run(): void
    {
        $users = User::take(10)->get();
        foreach ($users as $user) 
        {
            Cart::firstOrCreate(['user_id' => $user->id],['name'=> 'Cart for'. $user->name,'content' => '',]);
        }
    }
}