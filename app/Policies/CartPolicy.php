<?php

namespace App\Policies;

use App\Models\Cart;
use App\Models\User;

class CartPolicy
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

    public function view(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can('add a product to cart');
    }

    public function update(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    public function delete(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    public function restore(User $user, Cart $cart): bool
    {
        return $user->id === $cart->user_id;
    }

    public function forceDelete(User $user, Cart $cart): bool
    {
        return $user->hasRole('admin');
    }
}