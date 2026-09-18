<?php

namespace App\Repositories;

use App\Models\Reviews;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function __construct(
        protected Reviews $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with(['product', 'user']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('comment', 'LIKE', "%{$search}%");
        }

        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Reviews
    {
        return $this->model->with(['product', 'user'])->find($id);
    }

    public function findOrFail(int $id): Reviews
    {
        return $this->model->with(['product', 'user'])->findOrFail($id);
    }

    public function create(array $data): Reviews
    {
        return $this->model->create($data);
    }

    public function update(Reviews $model, array $data): Reviews
    {
        $model->update($data);
        return $model->fresh(['product', 'user']);
    }

    public function delete(Reviews $model): bool
    {
        return $model->delete();
    }

    public function getByProduct(int $productId): Collection
    {
        return $this->model->forProduct($productId)
            ->with('user')
            ->latest()
            ->get();
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with('product')
            ->latest()
            ->get();
    }

    public function averageRatingForProduct(int $productId): float
    {
      return round( (float) $this->model->where('product_id', $productId)->avg('rating'),2 );
    }
}