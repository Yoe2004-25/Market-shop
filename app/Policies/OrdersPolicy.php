<?php

namespace App\Policies;

use App\Models\Orders;
use App\Models\User;

class OrdersPolicy
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
      
        return $user->can('view own orders') || $user->can('manage orders');
    }

    public function view(User $user, Orders $order): bool
    {
        return $user->id === $order->user_id
            || $user->can('manage orders');
    }

    public function create(User $user): bool
    {
        return $user->can('create orders');
    }

    public function update(User $user, Orders $order): bool
    {
        return $user->can('manage orders');
    }

    public function delete(User $user, Orders $order): bool
    {
        return $user->can('manage orders');
    }

    public function restore(User $user, Orders $order): bool
    {
        return $user->can('manage orders');
    }

    public function forceDelete(User $user, Orders $order): bool
    {
        return $user->hasRole('admin');
    }
}