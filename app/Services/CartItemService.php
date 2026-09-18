<?php

namespace App\Services;

use App\Actions\CartItem\CreateCartItemAction;
use App\Actions\CartItem\DeleteCartItemAction;
use App\Actions\CartItem\UpdateCartItemAction;
use App\DTOs\CartItem\CreateCartItemDTO;
use App\DTOs\CartItem\UpdateCartItemDTO;
use App\Models\Cart_items;
use App\Repositories\CartItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CartItemService
{
    public const CACHE_TAG    = 'cart_items';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'cart_items.all';
    public const CACHE_SINGLE = 'cart_items.';

    public function __construct(
        private CartItemRepositoryInterface $repository,
        private CreateCartItemAction $createAction,
        private UpdateCartItemAction $updateAction,
        private DeleteCartItemAction $deleteAction,
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

    public function getById(int $id): Cart_items
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByCart(int $cartId): Collection
    {
        return $this->repository->getByCart($cartId);
    }

    public function create(CreateCartItemDTO $dto): Cart_items
    {
        return DB::transaction(function () use ($dto) {
            $item = $this->createAction->execute($dto);
            $this->clearCache();
            return $item;
        });
    }

    public function update(Cart_items $model, UpdateCartItemDTO $dto): Cart_items
    {
        return DB::transaction(function () use ($model, $dto) {
            $item = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $item;
        });
    }

    public function delete(Cart_items $model): bool
    {
        return DB::transaction(function () use ($model) {
            $result = $this->deleteAction->execute($model);
            $this->clearCache($model->id);
            return $result;
        });
    }

    public function clearCart(int $cartId): int
    {
        return DB::transaction(function () use ($cartId) {
            $deleted = $this->repository->clearCart($cartId);
            $this->clearCache();
            return $deleted;
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