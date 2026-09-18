<?php

namespace App\Policies;

use App\Models\Categories;
use App\Models\User;

class CategoriesPolicy
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

    public function view(User $user, Categories $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->can('manage categories');
    }

    public function update(User $user, Categories $category): bool
    {
        return $user->can('manage categories');
    }

    public function delete(User $user, Categories $category): bool
    {
        return $user->can('manage categories');
    }

    public function restore(User $user, Categories $category): bool
    {
        return $user->can('manage categories');
    }

    public function forceDelete(User $user, Categories $category): bool
    {
        return $user->can('manage categories');
    }
}