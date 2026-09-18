<?php

namespace App\Actions\Reviews;

use App\DTOs\Reviews\UpdateReviewDTO;
use App\Models\Reviews;
use App\Repositories\ReviewRepositoryInterface;

class UpdateReviewAction
{
    public function __construct(
        private ReviewRepositoryInterface $repository
    ) {}

    public function execute(Reviews $model, UpdateReviewDTO $dto): Reviews
    {
        return $this->repository->update($model, $dto->toArray());
    }
}