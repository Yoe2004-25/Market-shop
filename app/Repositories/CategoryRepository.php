<?php

namespace App\Repositories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        protected Categories $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->withCount('products');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->get();
    }

    public function findById(int $id): ?Categories
    {
        return $this->model->with('products')->find($id);
    }

    public function findOrFail(int $id): Categories
    {
        return $this->model->with('products')->findOrFail($id);
    }

    public function create(array $data): Categories
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Categories
    {
        $category = $this->model->findOrFail($id);
        $category->update($data);
        return $category->fresh();
    }

    public function delete(int $id): bool
    {
        $category = $this->model->findOrFail($id);
        return $category->delete();
    }

    public function findBySlug(string $slug): ?Categories
    {
        return $this->model->where('slug', $slug)->first();
    }
}