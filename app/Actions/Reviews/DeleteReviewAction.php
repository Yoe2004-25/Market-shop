<?php

namespace App\Actions\Reviews;

use App\Models\Reviews;
use App\Repositories\ReviewRepositoryInterface;

class DeleteReviewAction
{
    public function __construct( private ReviewRepositoryInterface $repository ) {}

    public function execute(Reviews $model): bool
    {
        return $this->repository->delete($model);
    }
}