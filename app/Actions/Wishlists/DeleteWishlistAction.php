<?php

namespace App\Actions\Wishlists;

use App\Models\wishlist;
use App\Repositories\WishlistRepositoryInterface;

class DeleteWishlistAction
{
    public function __construct( private WishlistRepositoryInterface $repository ) {}

    public function execute(wishlist $model): bool
    {
        return $this->repository->delete($model);
    }
}