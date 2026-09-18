<?php

namespace App\Actions\CartItem;

use App\DTOs\CartItem\UpdateCartItemDTO;
use App\Models\Cart_items;
use App\Repositories\CartItemRepositoryInterface;

class UpdateCartItemAction
{
    public function __construct(private CartItemRepositoryInterface $repository) {}

    public function execute(Cart_items $model, UpdateCartItemDTO $dto): Cart_items
    {
        return $this->repository->update($model, $dto->toArray());
    }
}