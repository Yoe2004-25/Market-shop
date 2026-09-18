<?php

namespace App\Observers;

use App\Models\Cart;
use App\Models\Cart_items;
use Illuminate\Support\Facades\Log;
use App\Models\Products;
class CartItemsObserver
{
    /**
     * 
     *  'cart_id' , 
        'product_id', 
        'quantity', 
        'price',
     * Handle the Cart_items "created" event.
     */
    public function created(Cart_items $cart_items): void
    {
        Log::info('Cart item created', [
            'cart_id' => $cart_items->cart_id,
            'product_id' => $cart_items->product_id,
            'quantity' => $cart_items->quantity,
            'price' => $cart_items->price,
        ]);

    }

    /**
     * Handle the Cart_items "updated" event.
     */
    public function updated(Cart_items $cart_items): void
    {
       Log::info('Cart item updated', [
            'id' => $cart_items->id,
            'quantity' => $cart_items->quantity,
            'price' => $cart_items->price,
        ]);
    }

    /**
     * Handle the Cart_items "deleted" event.
     */
    public function deleted(Cart_items $cart_items): void
    {
         Log::info('Cart item deleted', ['id' => $cart_items->id]);
    }

    /**
     * Handle the Cart_items "restored" event.
     */
    public function restored(Cart_items $cart_items): void
    {
        Log::info('Cart item restored', ['id' => $cart_items->id]);
    }

    /**
     * Handle the Cart_items "force deleted" event.
     */
    public function forceDeleted(Cart_items $cart_items): void
    {
         Log::info('Cart item finally deleted', ['id' => $cart_items->id]);
    }
}