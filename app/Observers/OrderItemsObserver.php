<?php

namespace App\Observers;

use App\Models\OrdersItems;
use Illuminate\Support\Facades\Log;

class OrderItemsObserver
{
    /**
     * Handle the OrderItems "created" event.
     * 
     *  'details',
        'order_id', 
        'product_id', 
        'price', 
        'quantity', 
        'subtotal', 
     */
    public function created(OrdersItems $orderItems): void
    {
        Log::info('Successfully created an orders item.', [
            'details' => $orderItems->details,
            'order_id' => $orderItems->order_id,
            'product_id' => $orderItems->product_id,
            'price' => $orderItems->price,
            'quantity' => $orderItems->quantity,
            'subtotal' => $orderItems->subtotal,
        ]);

        Log::info('New order item added: ' . $orderItems->details);
    }

    /**
     * Handle the OrderItems "updated" event.
     */
    public function updated(OrdersItems $orderItems): void
    {
        Log::info('Successfully updated an orders item.', [
            'details' => $orderItems->details,
            'order_id' => $orderItems->order_id,
            'product_id' => $orderItems->product_id,
            'price' => $orderItems->price,
            'quantity' => $orderItems->quantity,
            'subtotal' => $orderItems->subtotal,
        ]);

        Log::info('order item updated: ' . $orderItems->details);
    }

    /**
     * Handle the OrderItems "deleted" event.
     */
    public function deleted(OrdersItems $orderItems): void
    {
        Log::info('Successfully deleted an orders item.', [
            'details' => $orderItems->details,
            'order_id' => $orderItems->order_id,
            'product_id' => $orderItems->product_id,
            'price' => $orderItems->price,
            'quantity' => $orderItems->quantity,
            'subtotal' => $orderItems->subtotal,
        ]);

        Log::info('order item is deleted: ' . $orderItems->details);
    }

    /**
     * Handle the OrderItems "restored" event.
     */
   
}