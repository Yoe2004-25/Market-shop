<?php

namespace App\Policies;

use App\Models\User;
use App\Models\wishlist;

class WishlistPolicy
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

    public function view(User $user, wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    public function create(User $user): bool
    {
        return $user->can('create wishlist');
    }

    public function update(User $user, wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    public function delete(User $user, wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    public function restore(User $user, wishlist $wishlist): bool
    {
        return $user->id === $wishlist->user_id;
    }

    public function forceDelete(User $user, wishlist $wishlist): bool
    {
        return $user->hasRole('admin');
    }
}