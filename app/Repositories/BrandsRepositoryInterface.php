<?php

namespace App\Repositories;

use App\Models\Brands;
use Illuminate\Database\Eloquent\Collection;

interface BrandsRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Brands;

    public function findOrFail(int $id): Brands;

    public function create(array $data): Brands;

    public function update(Brands $model, array $data): Brands;

    public function delete(Brands $model): bool;

    public function findByName(string $name): ?Brands;
}