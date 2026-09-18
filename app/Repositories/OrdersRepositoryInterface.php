<?php

namespace App\Repositories;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Collection;

interface OrdersRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Orders;

    public function findOrFail(int $id): Orders;

    public function create(array $data): Orders;

    public function update(Orders $model, array $data): Orders;

    public function delete(Orders $model): bool;

    public function findByUser(int $userId): Collection;

    public function updateStatus(Orders $model, string $status): Orders;

    public function updatePaymentStatus(Orders $model, string $paymentStatus): Orders;
}