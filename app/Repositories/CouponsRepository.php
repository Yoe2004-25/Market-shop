<?php

namespace App\Repositories;

use App\Models\Coupons;
use App\Repositories\CouponsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CouponsRepository implements CouponsRepositoryInterface
{
    public function __construct(
        protected Coupons $model
    ) {}

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('code', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->get();
    }

    public function find(int $id): ?Coupons
    {
        return $this->model->find($id);
    }

    public function findOrFail(int $id): Coupons
    {
        return $this->model->findOrFail($id);
    }

    public function findByCode(string $code): ?Coupons
    {
        return $this->model->where('code', strtoupper($code))->first();
    }

    public function create(array $data): Coupons
    {
        return $this->model->create($data);
    }

    public function update(Coupons $model, array $data): Coupons
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Coupons $model): bool
    {
        return $model->delete();
    }

    public function getActive(): Collection
    {
        return $this->model->active()->get();
    }
}