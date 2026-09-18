<?php

namespace App\Repositories;

use App\Models\Orders;
use Illuminate\Database\Eloquent\Collection;

class OrdersRepository implements OrdersRepositoryInterface
{
    public function __construct(
        protected Orders $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()
            ->with(['user', 'coupon', 'items', 'payment']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_status'])) {
            $query->where('payment_status', $filters['payment_status']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Orders
    {
        return $this->model
            ->with(['user', 'coupon', 'items.product', 'payment'])
            ->find($id);
    }

    public function findOrFail(int $id): Orders
    {
        return $this->model
            ->with(['user', 'coupon', 'items.product', 'payment'])
            ->findOrFail($id);
    }

    public function create(array $data): Orders
    {
        return $this->model->create($data);
    }

    public function update(Orders $model, array $data): Orders
    {
        $model->update($data);
        return $model->fresh(['user', 'coupon', 'items', 'payment']);
    }

    public function delete(Orders $model): bool
    {
        return $model->delete();
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['coupon', 'items', 'payment'])
            ->latest()
            ->get();
    }

    public function updateStatus(Orders $model, string $status): Orders
    {
        $model->update(['status' => $status]);
        return $model->fresh();
    }

    public function updatePaymentStatus(Orders $model, string $paymentStatus): Orders
    {
        $model->update(['payment_status' => $paymentStatus]);
        return $model->fresh();
    }
}