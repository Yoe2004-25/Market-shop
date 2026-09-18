<?php

namespace App\Repositories;

use App\Models\wishlist;
use Illuminate\Database\Eloquent\Collection;

interface WishlistRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?wishlist;

    public function findOrFail(int $id): wishlist;

    public function create(array $data): wishlist;

    public function update(wishlist $model, array $data): wishlist;

    public function delete(wishlist $model): bool;

    public function getByUser(int $userId): Collection;

    public function findByUserAndProduct(int $userId, int $productId): ?wishlist;

    public function clearUserWishlist(int $userId): int;
}