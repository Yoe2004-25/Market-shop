<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

interface CartRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Cart;

    public function findOrFail(int $id): Cart;

    public function create(array $data): Cart;

    public function update(Cart $model, array $data): Cart;

    public function delete(Cart $model): bool;

    public function findByUser(int $userId): ?Cart;

    public function getOrCreateForUser(int $userId): Cart;
}