<?php

namespace App\Services;

use App\Actions\Reviews\CreateReviewAction;
use App\Actions\Reviews\DeleteReviewAction;
use App\Actions\Reviews\UpdateReviewAction;
use App\DTOs\Reviews\CreateReviewDTO;
use App\DTOs\Reviews\UpdateReviewDTO;
use App\Models\Reviews;
use App\Repositories\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public const CACHE_TAG    = 'reviews';
    public const CACHE_TTL    = 600;
    public const CACHE_ALL    = 'reviews.all';
    public const CACHE_SINGLE = 'reviews.';

    public function __construct(
        private ReviewRepositoryInterface $repository,
        private CreateReviewAction $createAction,
        private UpdateReviewAction $updateAction,
        private DeleteReviewAction $deleteAction,
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

    public function getById(int $id): Reviews
    {
        return Cache::tags([self::CACHE_TAG])->remember(
            self::CACHE_SINGLE . $id,
            self::CACHE_TTL,
            fn () => $this->repository->findOrFail($id)
        );
    }

    public function getByProduct(int $productId): Collection
    {
        return $this->repository->getByProduct($productId);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->repository->getByUser($userId);
    }

    public function getAverageRating(int $productId): float
    {
        return $this->repository->averageRatingForProduct($productId);
    }

    public function create(CreateReviewDTO $dto): Reviews
    {
        return DB::transaction(function () use ($dto) {
            $review = $this->createAction->execute($dto);
            $this->clearCache();
            return $review;
        });
    }

    public function update(Reviews $model, UpdateReviewDTO $dto): Reviews
    {
        return DB::transaction(function () use ($model, $dto) {
            $review = $this->updateAction->execute($model, $dto);
            $this->clearCache($model->id);
            return $review;
        });
    }

    public function delete(Reviews $model): bool
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