<?php

namespace App\Actions\Wishlists;

use App\DTOs\Wishlists\CreateWishlistDTO;
use App\Models\wishlist;
use App\Repositories\WishlistRepositoryInterface;

class CreateWishlistAction
{
    public function __construct(
        private WishlistRepositoryInterface $repository
    ) {}

    public function execute(CreateWishlistDTO $dto): wishlist
    {
        $existing = $this->repository->findByUserAndProduct(
            $dto->user_id,
            $dto->product_id
        );

        if ($existing) {
            return $existing;
        }

        return $this->repository->create($dto->toArray());
    }
}