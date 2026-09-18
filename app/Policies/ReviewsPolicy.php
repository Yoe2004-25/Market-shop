<?php

namespace App\Policies;

use App\Models\Reviews;
use App\Models\User;

class ReviewsPolicy
{
    public function before(User $user, string $ability): ?bool
    {
       
        if ($user->hasRole('admin') && $ability === 'delete') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reviews $review): bool
    {
        return true; 
    }

    public function create(User $user): bool
    {
        return $user->can('write review');
    }

    public function update(User $user, Reviews $review): bool
    {
        return $user->id === $review->user_id
            && $user->can('write review');
    }

    public function delete(User $user, Reviews $review): bool
    {
      
        return $user->id === $review->user_id  || $user->can('delete reviews');
    }

    public function restore(User $user, Reviews $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function forceDelete(User $user, Reviews $review): bool
    {
        return $user->hasRole('admin');
    }
}