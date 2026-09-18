<?php

namespace App\Observers;

use App\Models\Orders;
use Illuminate\Support\Facades\Log;

class OrdersObserver
{
    /**
     * Handle the Orders "created" event.
     */
    public function created(Orders $orders): void
    {
        Log::info('Successfully created a orders.', [
             'status'=>$orders->status , 
            'payment_status'=>$orders->payment_status, 
            'total'=>$orders->total, 
            'shipping_cost'=>$orders->shipping_cost,
            'tax'=>$orders->tax, 
            'grand_total'=>$orders->grand_total,
        ]);

        Log::info('new Orders id added'.$orders->name); 
    }

    /**
     * Handle the Orders "updated" event.
     */
    public function updated(Orders $orders): void
    {
         Log::info('Successfully Updated a orders.', [
             'status'=>$orders->status , 
            'payment_status'=>$orders->payment_status, 
            'total'=>$orders->total, 
            'shipping_cost'=>$orders->shipping_cost,
            'tax'=>$orders->tax, 
            'grand_total'=>$orders->grand_total,
        ]);

        Log::info('Orders updated'.$orders->name);
    }

    /**
     * Handle the Orders "deleted" event.
     */
    public function deleted(Orders $orders): void
    {
         Log::info('Successfully deleted a orders.', [
             'status'=>$orders->status , 
            'payment_status'=>$orders->payment_status, 
            'total'=>$orders->total, 
            'shipping_cost'=>$orders->shipping_cost,
            'tax'=>$orders->tax, 
            'grand_total'=>$orders->grand_total,
        ]);

        Log::info('deleted Order'.$orders->name);
    }

    /**
     * Handle the Orders "restored" event.
     */
    public function restored(Orders $orders): void
    {
         Log::info('Successfully created a orders.', [
             'status'=>$orders->status , 
            'payment_status'=>$orders->payment_status, 
            'total'=>$orders->total, 
            'shipping_cost'=>$orders->shipping_cost,
            'tax'=>$orders->tax, 
            'grand_total'=>$orders->grand_total,
        ]);

        Log::info('new Brands id added'.$orders->name);
    }

    /**
     * Handle the Orders "force deleted" event.
     */
    public function forceDeleted(Orders $orders): void
    {
         Log::info('Successfully Final deleted a orders.', [
             'status'=>$orders->status , 
            'payment_status'=>$orders->payment_status, 
            'total'=>$orders->total, 
            'shipping_cost'=>$orders->shipping_cost,
            'tax'=>$orders->tax, 
            'grand_total'=>$orders->grand_total,
        ]);

        Log::info('new Brands id added'.$orders->name);
    }
}