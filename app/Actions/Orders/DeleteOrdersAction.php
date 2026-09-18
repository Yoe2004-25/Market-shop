<?php

namespace App\Actions\Orders;

use App\Models\Orders;
use App\Repositories\OrdersRepositoryInterface;

class DeleteOrdersAction
{
    public function __construct(
        private OrdersRepositoryInterface $repository
    ) {}

    public function execute(Orders $model): bool
    {
        return $this->repository->delete($model);
    }
}