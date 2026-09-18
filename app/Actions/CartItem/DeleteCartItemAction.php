<?php

namespace App\Actions\CartItem;

use App\Models\Cart_items;
use App\Repositories\CartItemRepositoryInterface;

class DeleteCartItemAction
{
    public function __construct(private CartItemRepositoryInterface $repository) {}
    public function execute(Cart_items $model): bool
    {
        return $this->repository->delete($model);
    }
}