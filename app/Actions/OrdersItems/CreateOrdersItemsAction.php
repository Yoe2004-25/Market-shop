<?php

namespace App\Actions\OrdersItems;

use App\DTOs\OrdersItems\CreateOrdersItemsDTO;
use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;

class CreateOrdersItemsAction
{
    public function __construct(
        private OrdersItemsRepositoryInterface $repository
    ) {}

    public function execute(CreateOrdersItemsDTO $dto): OrdersItems
    {
        return $this->repository->create($dto->toArray());
    }
}