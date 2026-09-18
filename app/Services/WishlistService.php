<?php

namespace App\Services;

use App\Actions\Wishlists\CreateWishlistAction;
use App\Actions\Wishlists\DeleteWishlistAction;
use App\Actions\Wishlists\UpdateWishlistAction;
use App\DTOs\Wishlists\CreateWishlistDTO;
use App\DTOs\Wishlists\UpdateWishlistDTO;
use App\Models\wishlist;
use App\Repositories\WishlistRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WishlistService
{
    public const CACHE_TAG    = 'wishlists';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'wishlists.all';
    public const CACHE_SINGLE = 'wishlists.';

    public function __construct(
        private WishlistRepositoryInterface $repository,
        private CreateWishlistAction $createAction,
        private UpdateWishlistAction $updateAction,
        private DeleteWishlistAction $deleteAction,
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

    public function getById(int $id): wishlist
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByUser(int $userId): Collection
    {
        return $this->repository->getByUser($userId);
    }

    public function create(CreateWishlistDTO $dto): wishlist
    {
        return DB::transaction(function () use ($dto) {
            $item = $this->createAction->execute($dto);
            $this->clearCache();
            return $item;
        });
    }

    public function update(wishlist $model, UpdateWishlistDTO $dto): wishlist
    {
        return DB::transaction(function () use ($model, $dto) {
            $item = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $item;
        });
    }

    public function delete(wishlist $model): bool
    {
        return DB::transaction(function () use ($model) {
            $result = $this->deleteAction->execute($model);
            $this->clearCache($model->id);
            return $result;
        });
    }
    public function removeByUserAndProduct(int $userId, int $productId): bool
    {
        return DB::transaction(function () use ($userId, $productId) {
            $item = $this->repository->findByUserAndProduct($userId, $productId);
            if (!$item) {
                return false;
            }
            $result = $this->repository->delete($item);
            $this->clearCache();
            return $result;
        });
    }

    public function isInWishlist(int $userId, int $productId): bool
    {
        return (bool) $this->repository->findByUserAndProduct($userId, $productId);
    }

    private function clearCache(?int $id = null): void
    {
        Cache::tags([self::CACHE_TAG])->flush();

        if ($id) {
            Cache::tags([self::CACHE_TAG])->forget(self::CACHE_SINGLE . $id);
        }
    }
}