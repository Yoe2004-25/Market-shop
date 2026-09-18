<?php

namespace App\Observers;

use App\Models\Coupons;
use Illuminate\Support\Facades\Log;

class CouponsObserver
{
    /**
     * 
     *   'code', 
        'type', 
        'value', 
        'expire_date', // end of the time خصم 
        'usage_limit', 
        'status', 
     * Handle the Coupons "created" event.
     */
    public function created(Coupons $coupons): void
    {
        Log::info('Successfully created a coupon.', [
            'code' => $coupons->code,
            'type' => $coupons->type,
            'value' => $coupons->value,
            'expire_date' => $coupons->expire_date,
            'usage_limit' => $coupons->usage_limit,
            'status' => $coupons->status,
        ]);

        Log::info('New coupon added: ' . $coupons->code);
    }

    /**
     * Handle the Coupons "updated" event.
     */
    public function updated(Coupons $coupons): void
    {
        Log::info('Successfully updateded a coupon.', [
            'code' => $coupons->code,
            'type' => $coupons->type,
            'value' => $coupons->value,
            'expire_date' => $coupons->expire_date,
            'usage_limit' => $coupons->usage_limit,
            'status' => $coupons->status,
        ]);

        Log::info('coupon updated: ' . $coupons->code);
    }

    /**
     * Handle the Coupons "deleted" event.
     */
    public function deleted(Coupons $coupons): void
    {
        Log::info('Successfully deleted a coupon.', [
            'code' => $coupons->code,
            'type' => $coupons->type,
            'value' => $coupons->value,
            'expire_date' => $coupons->expire_date,
            'usage_limit' => $coupons->usage_limit,
            'status' => $coupons->status,
        ]);

        Log::info('coupon done delete: ' . $coupons->code);
    }

   
}