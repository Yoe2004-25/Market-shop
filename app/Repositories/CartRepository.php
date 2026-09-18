<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(protected Cart $model ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with(['user', 'items.product']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Cart
    {
        return $this->model->with(['user', 'items.product'])->find($id);
    }

    public function findOrFail(int $id): Cart
    {
        return $this->model->with(['user', 'items.product'])->findOrFail($id);
    }

    public function create(array $data): Cart
    {
        return $this->model->create($data);
    }

    public function update(Cart $model, array $data): Cart
    {
        $model->update($data);
        return $model->fresh(['user', 'items.product']);
    }

    public function delete(Cart $model): bool
    {
        return $model->delete();
    }

    public function findByUser(int $userId): ?Cart
    {
        return $this->model
            ->where('user_id', $userId)
            ->with(['items.product'])
            ->first();
    }

    public function getOrCreateForUser(int $userId): Cart
    {
        $cart = $this->findByUser($userId);

        if (!$cart) {
            $cart = $this->create([
                'user_id' => $userId,
                'name'    => 'Default Cart',
                'content' => '',
            ]);
        }

        return $cart;
    }
}