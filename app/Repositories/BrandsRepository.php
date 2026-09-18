<?php

namespace App\Repositories;

use App\Models\Brands;
use Illuminate\Database\Eloquent\Collection;

class BrandsRepository implements BrandsRepositoryInterface
{
    public function __construct(
        protected Brands $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery()->with('products');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'LIKE', "%{$search}%");
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Brands
    {
        return $this->model->with('products')->find($id);
    }

    public function findOrFail(int $id): Brands
    {
        return $this->model->with('products')->findOrFail($id);
    }

    public function create(array $data): Brands
    {
        return $this->model->create($data);
    }

    public function update(Brands $model, array $data): Brands
    {
        $model->update($data);
        return $model->fresh('products');
    }

    public function delete(Brands $model): bool
    {
        return $model->delete();
    }

    public function findByName(string $name): ?Brands
    {
        return $this->model->where('name', $name)->first();
    }
}