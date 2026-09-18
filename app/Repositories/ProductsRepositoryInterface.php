<?php

namespace App\Repositories;

use App\Models\Products;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductsRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator;

    public function find(int $id): ?Products;

    public function findOrFail(int $id): Products;

    public function findBySlug(string $slug): ?Products;

    public function create(array $data): Products;

    public function update(Products $model, array $data): Products;

    public function delete(Products $model): bool;

    public function getActive(): Collection;
}