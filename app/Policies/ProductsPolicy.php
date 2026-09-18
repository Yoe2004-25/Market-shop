<?php

namespace App\Policies;

use App\Models\Products;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductsPolicy
{
    /**
     * Determine whether the user can view any models.
     */


    public function before(User $user,  string $ability): ?bool
    {
        if($user->hasRole('admin'))
        {
            return true ; 
        }
        
        return null ; 
    } 
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Products $products): bool
    {
        return true ;

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create products');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Products $products): bool
    {
       return $user->id === $products->user_id && $user->can('update products');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Products $products): bool
    {
       return $user->id === $products->user_id && $user->can('delete products');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Products $products): bool
    {
            return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Products $products): bool
    {
            return $user->hasRole('admin');
    }
}