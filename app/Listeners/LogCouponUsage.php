<?php

namespace App\Listeners;

use App\Events\CouponUsed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogCouponUsage implements ShouldQueue
{
    
    public int $tries = 3;

   
    public int $backoff = 5;

    public function handle(CouponUsed $event): void
    {
        Log::info('🎟️ Coupon used', [
            'coupon_id'  => $event->coupon->id,
            'code'       => $event->coupon->code,
            'user_id'    => $event->user->id,
            'user_email' => $event->user->email,
            'discount'   => $event->discount,
            'total'      => $event->total,
            'final'      => $event->total - $event->discount,
            'order_id'   => $event->orderId,
            'used_at'    => now()->toDateTimeString(),
        ]);
    }

    /**
     * لو الـ Listener فشل نهائيًا.
     */
    public function failed(CouponUsed $event, \Throwable $exception): void
    {
        Log::error('❌ CouponUsed listener failed', [
            'coupon_id' => $event->coupon->id,
            'user_id'   => $event->user->id,
            'error'     => $exception->getMessage(),
        ]);
    }
}