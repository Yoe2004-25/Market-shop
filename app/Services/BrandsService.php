<?php

namespace App\Services;

use App\Actions\Brands\CreateBrandsAction;
use App\Actions\Brands\DeleteBrandsAction;
use App\Actions\Brands\UpdateBrandsAction;
use App\DTOs\Brands\CreateBrandsDTO;
use App\DTOs\Brands\UpdateBrandsDTO;
use App\Models\Brands;
use App\Repositories\BrandsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BrandsService
{
    public const CACHE_TAG    = 'brands';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'brands.all';
    public const CACHE_SINGLE = 'brands.';

    public function __construct(
        private BrandsRepositoryInterface $repository,
        private CreateBrandsAction $createAction,
        private UpdateBrandsAction $updateAction,
        private DeleteBrandsAction $deleteAction,
    ) {}

    public function getAll(array $filters = []): Collection
    {
        $cacheKey = self::CACHE_ALL . '.' . md5(json_encode($filters));

        return Cache::tags([self::CACHE_TAG])->remember(
            $cacheKey,
            self::CACHE_TTL,
            fn () => $this->repository->all($filters)
        );
    }

    public function getById(int $id): Brands
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function create(CreateBrandsDTO $dto): Brands
    {
        return DB::transaction(function () use ($dto) {
            $brand = $this->createAction->execute($dto);
            $this->clearCache();
            return $brand;
        });
    }

    public function update(Brands $model, UpdateBrandsDTO $dto): Brands
    {
        return DB::transaction(function () use ($model, $dto) {
            $brand = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $brand;
        });
    }

    public function delete(Brands $model): bool
    {
        return DB::transaction(function () use ($model) {
            $result = $this->deleteAction->execute($model);
            $this->clearCache($model->id);
            return $result;
        });
    }

    private function clearCache(?int $id = null): void
    {
        Cache::tags([self::CACHE_TAG])->flush();

        if ($id) {
            Cache::tags([self::CACHE_TAG])->forget(self::CACHE_SINGLE . $id);
        }
    }
}