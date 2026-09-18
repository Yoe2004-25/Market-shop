<?php

namespace App\Repositories;

use App\Models\OrdersItems;
use Illuminate\Database\Eloquent\Collection;

interface OrdersItemsRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?OrdersItems;

    public function findOrFail(int $id): OrdersItems;

    public function create(array $data): OrdersItems;

    public function update(OrdersItems $model, array $data): OrdersItems;

    public function delete(OrdersItems $model): bool;

    public function findByUser(int $userId): Collection;
}