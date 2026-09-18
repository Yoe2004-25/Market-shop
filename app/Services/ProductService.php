<?php

namespace App\Services;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\DeleteProductAction;
use App\Actions\Products\UpdateProductAction;
use App\DTOs\Products\CreateProductDTO;
use App\DTOs\Products\UpdateProductDTO;
use App\Models\Products;
use App\Repositories\ProductsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public const CACHE_TAG    = 'products';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'products.all';
    public const CACHE_SINGLE = 'products.';

    public function __construct(
        private ProductsRepositoryInterface $repository,
        private CreateProductAction $createAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction, ) {}

    public function getAll(array $filters = []): Collection
    {
        $cacheKey = self::CACHE_ALL . '.' . md5(json_encode($filters));

        return Cache::tags([self::CACHE_TAG])->remember(
            $cacheKey,
            self::CACHE_TTL,
            fn () => $this->repository->all($filters)
        );
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $filters);
    }

    public function getById(int $id): Products
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getActive(): Collection
    {
        return $this->repository->getActive();
    }

    public function create(CreateProductDTO $dto): Products
    {
        return DB::transaction(function () use ($dto) {
            $product = $this->createAction->execute($dto);
            $this->clearCache();
            return $product;
        });
    }

    public function update(Products $model, UpdateProductDTO $dto): Products
    {
        return DB::transaction(function () use ($model, $dto) {
            $product = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $product;
        });
    }

    public function delete(Products $model): bool
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