<?php

namespace Database\Seeders;

use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Products;
use Illuminate\Database\Seeder;

class OrdersItemsSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        $orders=Orders::all();
        $products=Products::take(20)->get();
        if ($orders->isEmpty() || $products->isEmpty())
        {
            $this->command->warn('ordersitems.');
            return;
        }
        foreach ($orders as $order) {
            foreach ($products->random(rand(1, 4)) as $product) {
                $quantity = rand(1, 3);
                $price    = $product->price;

                OrdersItems::firstOrCreate(
                    ['order_id' => $order->id, 'product_id' => $product->id],['user_id'=>$order->user_id,'quantity'=>$quantity,'price'=> $price,'subtotal' => $price * $quantity,'details'=>'itemorder->'.$order->id,]
                );
            }
        }
    }
}