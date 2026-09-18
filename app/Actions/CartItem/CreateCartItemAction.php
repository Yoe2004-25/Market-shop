<?php

namespace App\Actions\CartItem;

use App\DTOs\CartItem\CreateCartItemDTO;
use App\Models\Cart_items;
use App\Repositories\CartItemRepositoryInterface;

class CreateCartItemAction
{
    public function __construct(
        private CartItemRepositoryInterface $repository
    ) {}

    public function execute(CreateCartItemDTO $dto): Cart_items
    {
    
        $existing = $this->repository->findByCartAndProduct(
            $dto->cart_id,
            $dto->product_id
        );

        if ($existing) {
            return $this->repository->update($existing, [
                'quantity' => $existing->quantity + $dto->quantity,
            ]);
        }

        return $this->repository->create($dto->toArray());
    }
}