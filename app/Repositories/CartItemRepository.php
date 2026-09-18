<?php

namespace App\Repositories;

use App\Models\Cart_items;
use Illuminate\Database\Eloquent\Collection;

class CartItemRepository implements CartItemRepositoryInterface
{
    public function __construct(
        protected Cart_items $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with(['cart', 'product']);

        if (!empty($filters['cart_id'])) {
            $query->where('cart_id', $filters['cart_id']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Cart_items
    {
        return $this->model->with(['cart', 'product'])->find($id);
    }

    public function findOrFail(int $id): Cart_items
    {
        return $this->model->with(['cart', 'product'])->findOrFail($id);
    }

    public function create(array $data): Cart_items
    {
        return $this->model->create($data);
    }

    public function update(Cart_items $model, array $data): Cart_items
    {
        $model->update($data);
        return $model->fresh(['cart', 'product']);
    }

    public function delete(Cart_items $model): bool
    {
        return $model->delete();
    }

    public function getByCart(int $cartId): Collection
    {
        return $this->model
            ->where('cart_id', $cartId)
            ->with(['product'])
            ->get();
    }

    public function findByCartAndProduct(int $cartId, int $productId): ?Cart_items
    {
        return $this->model
            ->where('cart_id', $cartId)
            ->where('product_id', $productId)
            ->first();
    }

    public function clearCart(int $cartId): int
    {
        return $this->model->where('cart_id', $cartId)->delete();
    }
}