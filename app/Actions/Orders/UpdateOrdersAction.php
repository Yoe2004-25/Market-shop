<?php

namespace App\Actions\Orders;

use App\DTOs\Orders\UpdateOrdersDTO;
use App\Models\Orders;
use App\Repositories\OrdersRepositoryInterface;

class UpdateOrdersAction
{
    public function __construct(
        private OrdersRepositoryInterface $repository
    ) {}

    public function execute(Orders $model, UpdateOrdersDTO $dto): Orders
    {
        return $this->repository->update($model, $dto->toArray());
    }
}