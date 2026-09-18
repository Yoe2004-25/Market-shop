<?php

namespace App\Services;

use App\Actions\OrdersItems\CreateOrdersItemsAction;
use App\Actions\OrdersItems\DeleteOrdersItemsAction;
use App\Actions\OrdersItems\UpdateOrdersItemsAction;
use App\DTOs\OrdersItems\CreateOrdersItemsDTO;
use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrdersItemsService
{
    public const CACHE_TAG    = 'ordersItems';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'ordersItems.all';
    public const CACHE_SINGLE = 'ordersItems.';

    public function __construct(
        private OrdersItemsRepositoryInterface $repository,
        private CreateOrdersItemsAction $createAction,
        private UpdateOrdersItemsAction $updateAction,
        private DeleteOrdersItemsAction $deleteAction,
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

    public function getById(int $id): OrdersItems
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByUser(int $userId): Collection
    {
        return $this->repository->findByUser($userId);
    }

    public function create(CreateOrdersItemsDTO $dto): OrdersItems
    {
        return DB::transaction(function () use ($dto) {
            $item = $this->createAction->execute($dto);
            $this->clearCache();
            return $item;
        });
    }

    public function update(OrdersItems $model, UpdateOrdersItemsDTO $dto): OrdersItems
    {
        return DB::transaction(function () use ($model, $dto) {
            $item = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $item;
        });
    }

    public function delete(OrdersItems $model): bool
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