<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Payments; 
use App\Models\Orders;
class PaymentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Orders::doesntHave('payment')->take(15)->get();
        foreach ($orders as $order) {
            Payments::create([
                'order_id'=>$order->id,
                'payment_method'=>rand(0, 1) ? 'cash' : 'visa',
                'transaction_id'=>'TXN-' . strtoupper(uniqid()),
                'status'=>$order->status === 'success' ? 'completed' : 'pending',
                'amount'=>$order->grand_total,]);
        }
    }
}