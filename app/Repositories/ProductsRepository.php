<?php

namespace App\Repositories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductsRepository implements ProductsRepositoryInterface
{
    public function __construct(
        protected Products $model
    ) {}

    public function all(array $filters = []): Collection
    {
        return $this->buildQuery($filters)->latest()->get();
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->buildQuery($filters)->latest()->paginate($perPage);
    }

    public function find(int $id): ?Products
    {
        return $this->model->with(['category', 'brand', 'images'])->find($id);
    }

    public function findOrFail(int $id): Products
    {
        return $this->model->with(['category', 'brand', 'images'])->findOrFail($id);
    }

    public function findBySlug(string $slug): ?Products
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function create(array $data): Products
    {
        return $this->model->create($data);
    }

    public function update(Products $model, array $data): Products
    {
        $model->update($data);
        return $model->fresh(['category', 'brand', 'images']);
    }

    public function delete(Products $model): bool
    {
        return $model->delete();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->with(['category', 'brand'])->get();
    }


    private function buildQuery(array $filters)
    {
        $query = $this->model->newQuery()->with(['category', 'brand', 'images']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query;
    }
}