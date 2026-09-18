<?php

namespace App\Actions\Orders;

use App\DTOs\Orders\CreateOrdersDTO;
use App\Models\Orders;
use App\Repositories\OrdersRepositoryInterface;

class CreateOrdersAction
{
    public function __construct(
        private OrdersRepositoryInterface $repository
    ) {}

    public function execute(CreateOrdersDTO $dto): Orders
    {
        return $this->repository->create($dto->toArray());
    }
}