<?php

namespace App\Actions\OrdersItems;

use App\DTOs\OrdersItems\UpdateOrdersItemsDTO;
use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;

class UpdateOrdersItemsAction
{
    public function __construct(private OrdersItemsRepositoryInterface $repository) {}

    public function execute(OrdersItems $model, UpdateOrdersItemsDTO $dto): OrdersItems
    {
        return $this->repository->update($model, $dto->toArray());
    }
}