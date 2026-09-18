<?php

namespace App\Actions\Coupons;

use App\DTOs\Coupons\CreateCouponsDTO;
use App\Models\Coupons;
use App\Repositories\CouponsRepositoryInterface;

class CreateCouponsAction
{
    public function __construct(
        private CouponsRepositoryInterface $repository
    ) {}

    public function execute(CreateCouponsDTO $dto): Coupons
    {
        return $this->repository->create($dto->toArray());
    }
}