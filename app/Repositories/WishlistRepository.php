<?php

namespace App\Repositories;

use App\Models\wishlist;
use Illuminate\Database\Eloquent\Collection;

class WishlistRepository implements WishlistRepositoryInterface
{
    public function __construct(
        protected wishlist $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with(['user', 'product']);

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?wishlist
    {
        return $this->model->with(['user', 'product'])->find($id);
    }

    public function findOrFail(int $id): wishlist
    {
        return $this->model->with(['user', 'product'])->findOrFail($id);
    }

    public function create(array $data): wishlist
    {
        return $this->model->create($data);
    }

    public function update(wishlist $model, array $data): wishlist
    {
        $model->update($data);
        return $model->fresh(['user', 'product']);
    }

    public function delete(wishlist $model): bool
    {
        return $model->delete();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model
            ->forUser($userId)
            ->with('product')
            ->latest()
            ->get();
    }

    public function findByUserAndProduct(int $userId, int $productId): ?wishlist
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
    }

    public function clearUserWishlist(int $userId): int
    {
        return $this->model->where('user_id', $userId)->delete();
    }
}