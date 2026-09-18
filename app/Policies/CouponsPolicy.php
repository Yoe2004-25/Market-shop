<?php

namespace App\Policies;

use App\Models\Coupons;
use App\Models\User;

class CouponsPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('manage coupons');
    }

    public function view(User $user, Coupons $coupon): bool
    {
        return $user->can('manage coupons');
    }

    public function create(User $user): bool
    {
        return $user->can('create coupons');
    }

    public function update(User $user, Coupons $coupon): bool
    {
        return $user->can('manage coupons');
    }

    public function delete(User $user, Coupons $coupon): bool
    {
        return $user->can('manage coupons');
    }

    public function restore(User $user, Coupons $coupon): bool
    {
        return $user->can('manage coupons');
    }

    public function forceDelete(User $user, Coupons $coupon): bool
    {
        return $user->can('manage coupons');
    }

    
   
}