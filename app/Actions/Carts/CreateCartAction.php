<?php

namespace App\Actions\Carts;

use App\DTOs\Carts\CreateCartDTO;
use App\Models\Cart;
use App\Repositories\CartRepositoryInterface;

class CreateCartAction
{
    public function __construct( private CartRepositoryInterface $repository) {}

    public function execute(CreateCartDTO $dto): Cart
    {
        return $this->repository->create($dto->toArray());
    }
}