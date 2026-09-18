<?php

namespace App\Policies;

use App\Models\OrdersItems;
use App\Models\User;

class OrdersItemsPolicy
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
        return true;
    }

    public function view(User $user, OrdersItems $item): bool
    {
        return $user->id === $item->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can('create orders');
    }

    public function update(User $user, OrdersItems $item): bool
    {
        return $user->id === $item->user_id && $user->can('manage orders');
    }

    public function delete(User $user, OrdersItems $item): bool
    {
        return $user->id === $item->user_id && $user->can('manage orders');
    }

    public function restore(User $user, OrdersItems $item): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, OrdersItems $item): bool
    {
        return $user->hasRole('admin');
    }
}