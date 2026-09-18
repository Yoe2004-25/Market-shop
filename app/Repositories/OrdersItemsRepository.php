<?php

namespace App\Repositories;

use App\Models\OrdersItems;
use Illuminate\Database\Eloquent\Collection;

class OrdersItemsRepository implements OrdersItemsRepositoryInterface
{
    public function __construct(
        protected OrdersItems $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with(['order', 'product']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('details', 'LIKE', "%{$search}%")
                  ->orWhere('order_id', 'LIKE', "%{$search}%")
                  ->orWhere('product_id', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['price'])) {
            $query->where('price', $filters['price']);
        }

        if (!empty($filters['quantity'])) {
            $query->where('quantity', $filters['quantity']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?OrdersItems
    {
        return $this->model->with(['order', 'product'])->find($id);
    }

    public function findOrFail(int $id): OrdersItems
    {
        return $this->model->with(['order', 'product'])->findOrFail($id);
    }

    public function create(array $data): OrdersItems
    {
        return $this->model->create($data);
    }

    public function update(OrdersItems $model, array $data): OrdersItems
    {
        $model->update($data);
        return $model->fresh(['order', 'product']);
    }

    public function delete(OrdersItems $model): bool
    {
        return $model->delete();
    }

    public function findByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['order', 'product'])
            ->latest()
            ->get();
    }
}