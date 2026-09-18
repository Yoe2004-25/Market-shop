<?php

namespace App\Services;

use App\Actions\Coupons\CreateCouponsAction;
use App\Actions\Coupons\DeleteCouponsAction;
use App\Actions\Coupons\UpdateCouponsAction;
use App\DTOs\Coupons\CreateCouponsDTO;
use App\DTOs\Coupons\UpdateCouponsDTO;
use App\Models\Coupons;
use App\Repositories\CouponsRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CouponsService
{
    public const CACHE_TAG    = 'coupons';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'coupons.all';
    public const CACHE_SINGLE = 'coupons.';

    public function __construct(
        private CouponsRepositoryInterface $repository,
        private CreateCouponsAction $createAction,
        private UpdateCouponsAction $updateAction,
        private DeleteCouponsAction $deleteAction,
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

    public function getById(int $id): Coupons
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByCode(string $code): ?Coupons
    {
        return $this->repository->findByCode($code);
    }

    public function getActive(): Collection
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            'coupons.active',
            self::CACHE_TTL,
            fn () => $this->repository->getActive()
        );
    }

    public function create(CreateCouponsDTO $dto): Coupons
    {
        return DB::transaction(function () use ($dto) {
            $coupon = $this->createAction->execute($dto);
            $this->clearCache();
            return $coupon;
        });
    }

    public function update(Coupons $model, UpdateCouponsDTO $dto): Coupons
    {
        return DB::transaction(function () use ($model, $dto) {
            $coupon = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $coupon;
        });
    }

    public function delete(Coupons $model): bool
    {
        return DB::transaction(function () use ($model) {
            $result = $this->deleteAction->execute($model);
            $this->clearCache($model->id);
            return $result;
        });
    }

    /**
     * Validate a coupon and return discount for a given total.
     */
    public function validateAndApply(string $code, float $total): array
    {
        $coupon = $this->repository->findByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Coupon not found.', 'discount' => 0];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Coupon is expired or inactive.', 'discount' => 0];
        }

        return [
            'valid'    => true,
            'message'  => 'Coupon applied successfully.',
            'discount' => $coupon->calculateDiscount($total),
            'coupon'   => $coupon,
        ];
    }

    private function clearCache(?int $id = null): void
    {
        Cache::tags([self::CACHE_TAG])->flush();

        if ($id) {
            Cache::tags([self::CACHE_TAG])->forget(self::CACHE_SINGLE . $id);
        }
    }
}