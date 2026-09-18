<?php

namespace App\Events;

use App\Models\Coupons;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CouponUsed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Coupons $coupon,
        public User $user,
        public float $discount,
        public float $total,
        public ?string $orderId = null,
    ) {}
}