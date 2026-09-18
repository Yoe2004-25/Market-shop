<?php

namespace App\Actions\Wishlists;

use App\DTOs\Wishlists\UpdateWishlistDTO;
use App\Models\wishlist;
use App\Repositories\WishlistRepositoryInterface;

class UpdateWishlistAction
{
    public function __construct( private WishlistRepositoryInterface $repository) {}

    public function execute(wishlist $model, UpdateWishlistDTO $dto): wishlist
    {
        return $this->repository->update($model, $dto->toArray());
    }
}