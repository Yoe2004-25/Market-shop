<?php

namespace App\Services;

use App\Actions\Categories\CreateCategoryAction;
use App\Actions\Categories\DeleteCategoryAction;
use App\Actions\Categories\UpdateCategoryAction;
use App\DTOs\Category\CategoryDTO;
use App\Models\Categories;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public const CACHE_TAG    = 'categories';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'categories.all';
    public const CACHE_SINGLE = 'categories.';

    public function __construct(
        private CategoryRepositoryInterface $repository,
        private CreateCategoryAction $createAction,
        private UpdateCategoryAction $updateAction,
        private DeleteCategoryAction $deleteAction,
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

    public function getById(int $id): Categories
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function create(CategoryDTO $dto): Categories
    {
        return DB::transaction(function () use ($dto) {
            $category = $this->createAction->execute($dto);
            $this->clearCache();
            return $category;
        });
    }

    public function update(int $id, CategoryDTO $dto): Categories
    {
        return DB::transaction(function () use ($id, $dto) {
            $category = $this->updateAction->execute($id, $dto);
            $this->clearCache($id);
            return $category;
        });
    }

    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $result = $this->deleteAction->execute($id);
            $this->clearCache($id);
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