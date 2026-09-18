<?php

namespace App\Repositories;

use App\Models\Cart_items;
use Illuminate\Database\Eloquent\Collection;

interface CartItemRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Cart_items;

    public function findOrFail(int $id): Cart_items;

    public function create(array $data): Cart_items;

    public function update(Cart_items $model, array $data): Cart_items;

    public function delete(Cart_items $model): bool;

    public function getByCart(int $cartId): Collection;

    public function findByCartAndProduct(int $cartId, int $productId): ?Cart_items;

    public function clearCart(int $cartId): int;
}