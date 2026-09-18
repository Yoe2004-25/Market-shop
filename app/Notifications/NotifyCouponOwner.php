<?php

namespace App\Listeners;

use App\Events\CouponUsed;
use App\Models\User;
use App\Notifications\CouponUsedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyCouponOwner implements ShouldQueue
{
    public int $tries = 3;

    public function handle(CouponUsed $event): void
    {
      
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new CouponUsedNotification(
                $event->coupon,
                $event->user,
                $event->discount
            ));
        }

        // 2) بلّغ صاحب الكوبون لو موجود (مثلاً Seller)
        if ($event->coupon->owner_id ?? null) {
            $owner = User::find($event->coupon->owner_id);

            if ($owner && !$admins->contains('id', $owner->id)) {
                $owner->notify(new CouponUsedNotification(
                    $event->coupon,
                    $event->user,
                    $event->discount
                ));
            }
        }
    }
}