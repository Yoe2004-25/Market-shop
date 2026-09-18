<?php

namespace App\Repositories;

use App\Models\Reviews;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Reviews;

    public function findOrFail(int $id): Reviews;

    public function create(array $data): Reviews;

    public function update(Reviews $model, array $data): Reviews;

    public function delete(Reviews $model): bool;

    public function getByProduct(int $productId): Collection;

    public function getByUser(int $userId): Collection;

    public function averageRatingForProduct(int $productId): float;
}