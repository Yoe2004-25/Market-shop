<?php

namespace App\Policies;

use App\Models\Product_images;
use App\Models\User;

class ProductImagesPolicy
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

    public function view(User $user, Product_images $image): bool
    {
        return true; 
    }

    public function create(User $user): bool
    {
        return $user->can('manage products');
    }

    public function update(User $user, Product_images $image): bool
    {
      
        return $user->id === $image->product?->user_id && $user->can('manage products');
    }

    public function delete(User $user, Product_images $image): bool
    {
        return $user->id === $image->product?->user_id && $user->can('manage products');
    }

    public function restore(User $user, Product_images $image): bool
    {
        return $user->can('manage products');
    }

    public function forceDelete(User $user, Product_images $image): bool
    {
        return $user->hasRole('admin');
    }
}