<?php

namespace App\Services;

use App\Actions\Carts\CreateCartAction;
use App\Actions\Carts\DeleteCartAction;
use App\Actions\Carts\UpdateCartAction;
use App\DTOs\Carts\CreateCartDTO;
use App\DTOs\Carts\UpdateCartDTO;
use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CartService
{
    public const CACHE_TAG    = 'carts';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'carts.all';
    public const CACHE_SINGLE = 'carts.';

    public function __construct(
        private CartRepositoryInterface $repository,
        private CreateCartAction $createAction,
        private UpdateCartAction $updateAction,
        private DeleteCartAction $deleteAction,
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

    public function getById(int $id): Cart
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByUser(int $userId): ?Cart
    {
        return $this->repository->findByUser($userId);
    }

    public function getOrCreateForUser(int $userId): Cart
    {
        return $this->repository->getOrCreateForUser($userId);
    }

    public function create(CreateCartDTO $dto): Cart
    {
        return DB::transaction(function () use ($dto) {
            $cart = $this->createAction->execute($dto);
            $this->clearCache();
            return $cart;
        });
    }

    public function update(Cart $model, UpdateCartDTO $dto): Cart
    {
        return DB::transaction(function () use ($model, $dto) {
            $cart = $this->updateAction->execute(
                $model,
                method_exists($dto, 'toArray') ? $dto->toArray() : (array) $dto
            );
            $this->clearCache($model->id);
            return $cart;
        });
    }

    public function delete(Cart $model): bool
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