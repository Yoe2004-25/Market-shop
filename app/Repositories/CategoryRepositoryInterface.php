<?php

namespace App\Repositories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function findById(int $id): ?Categories;
    public function findOrFail(int $id): Categories;
    public function create(array $data): Categories;
    public function update(int $id, array $data): Categories;
    public function delete(int $id): bool;
    public function findBySlug(string $slug): ?Categories;
}