<?php

namespace App\Actions\Reviews;

use App\DTOs\Reviews\CreateReviewDTO;
use App\Models\Reviews;
use App\Repositories\ReviewRepositoryInterface;

class CreateReviewAction
{
    public function __construct(
        private ReviewRepositoryInterface $repository
    ) {}

    public function execute(CreateReviewDTO $dto): Reviews
    {
        return $this->repository->create($dto->toArray());
    }
}