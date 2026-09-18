<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;

class UpdateCartAction
{
    public function __construct(
        private CartRepositoryInterface $repository
    ) {}

    public function execute(Cart $model, array $data): Cart
    {
        return $this->repository->update($model, $data);
    }
}