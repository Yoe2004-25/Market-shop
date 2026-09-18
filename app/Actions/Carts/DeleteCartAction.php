<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;

class DeleteCartAction
{
    public function __construct(
        private CartRepositoryInterface $repository
    ) {}

    public function execute(Cart $model): bool
    {
        return $this->repository->delete($model);
    }
}