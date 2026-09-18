<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Orders;
use App\Models\Products;

class SendOrderNotifications
{
    /**
     * Create the event listener.
     */
    protected Orders $order;

    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $this->order;

        foreach ($order->items as $items) {     
           Products::where('id', $items->product_id)
                     ->decrement('stock', $items->quantity);
        }
    }
}