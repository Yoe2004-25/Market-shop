<?php

namespace App\Actions\OrdersItems;

use App\Models\OrdersItems;
use App\Repositories\OrdersItemsRepositoryInterface;

class DeleteOrdersItemsAction
{
    public function __construct(
        private OrdersItemsRepositoryInterface $repository
    ) {}

    public function execute(OrdersItems $model): bool
    {
        return $this->repository->delete($model);
    }
}