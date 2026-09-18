<?php

namespace App\Actions\Coupons;

use App\Models\Coupons;
use App\Repositories\CouponsRepositoryInterface;

class DeleteCouponsAction
{
    public function __construct(
        private CouponsRepositoryInterface $repository
    ) {}

    public function execute(Coupons $model): bool
    {
        return $this->repository->delete($model);
    }
}