<?php

namespace App\Services;

use App\Actions\Orders\CreateOrdersAction;
use App\Actions\Orders\DeleteOrdersAction;
use App\Actions\Orders\UpdateOrdersAction;
use App\DTOs\Orders\CreateOrdersDTO;
use App\DTOs\Orders\UpdateOrdersDTO;
use App\Models\Orders;
use App\Repositories\OrdersRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OrdersService
{
    public const CACHE_TAG    = 'orders';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'orders.all';
    public const CACHE_SINGLE = 'orders.';

    public function __construct(
        private OrdersRepositoryInterface $repository,
        private CreateOrdersAction $createAction,
        private UpdateOrdersAction $updateAction,
        private DeleteOrdersAction $deleteAction,
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

    public function getById(int $id): Orders
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

    public function create(CreateOrdersDTO $dto): Orders
    {
        return DB::transaction(function () use ($dto) {
            $order = $this->createAction->execute($dto);
            $this->clearCache();
            return $order;
        });
    }

    public function update(Orders $model, UpdateOrdersDTO $dto): Orders
    {
        return DB::transaction(function () use ($model, $dto) {
            $order = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $order;
        });
    }

    public function delete(Orders $model): bool
    {
        return DB::transaction(function () use ($model) {
            $result = $this->deleteAction->execute($model);
            $this->clearCache($model->id);
            return $result;
        });
    }

    public function updateStatus(Orders $model, string $status): Orders
    {
        $order = $this->repository->updateStatus($model, $status);
        $this->clearCache($model->id);
        return $order;
    }

    public function updatePaymentStatus(Orders $model, string $paymentStatus): Orders
    {
        $order = $this->repository->updatePaymentStatus($model, $paymentStatus);
        $this->clearCache($model->id);
        return $order;
    }

    private function clearCache(?int $id = null): void
    {
        Cache::tags([self::CACHE_TAG])->flush();

        if ($id) {
            Cache::tags([self::CACHE_TAG])->forget(self::CACHE_SINGLE . $id);
        }
    }
}