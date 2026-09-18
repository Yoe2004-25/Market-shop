<?php

namespace App\Repositories;

use App\Models\Coupons;
use Illuminate\Database\Eloquent\Collection;

interface CouponsRepositoryInterface
{
    public function all(array $filters = []): Collection;

    public function find(int $id): ?Coupons;

    public function findOrFail(int $id): Coupons;

    public function findByCode(string $code): ?Coupons;

    public function create(array $data): Coupons;

    public function update(Coupons $model, array $data): Coupons;

    public function delete(Coupons $model): bool;

    public function getActive(): Collection;
}