<?php

namespace App\Actions\Coupons;

use App\DTOs\Coupons\UpdateCouponsDTO;
use App\Models\Coupons;
use App\Repositories\CouponsRepositoryInterface;

class UpdateCouponsAction
{
    public function __construct(
        private CouponsRepositoryInterface $repository
    ) {}

    public function execute(Coupons $model, UpdateCouponsDTO $dto): Coupons
    {
        return $this->repository->update($model, $dto->toArray());
    }
}