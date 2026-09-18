<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Orders;
use App\Models\Coupons; 
use App\Models\User; 

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $users   = User::take(10)->get();
        $coupons = Coupons::where('status', 'active')->get();
        if ($users->isEmpty()) 
        {
            $this->command->warn('Seed users first.');
            return;
        }
        foreach ($users as $user) {
        for ($i = 0; $i < rand(1, 3); $i++) {
                Orders::factory()->state(['user_id'=> $user->id,'coupon_id' => $coupons->isNotEmpty() && rand(0, 1) ? $coupons->random()->id : null,])->create();
            }
        }
        Orders::factory()->count(10)->successful()->create();
    }
}