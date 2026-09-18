<?php

namespace App\Policies;

use App\Models\Brands;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BrandsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Brands $brands): bool
    {
        return true ; 
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage brands') ; 
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Brands $brands): bool
    {
        return $user->can('manage brands'); 
    }

   
   
     public function delete(User $user, Brands $brand): bool
    {
        return $user->can('manage brands');
    }

    public function restore(User $user, Brands $brand): bool
    {
        return $user->can('manage brands');
    }

    public function forceDelete(User $user, Brands $brand): bool
    {
        return $user->can('manage brands');
    }
}