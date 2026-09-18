<?php

namespace App\Actions\Products;

use App\DTOs\Products\CreateProductDTO;
use App\Models\Products;
use App\Repositories\ProductsRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CreateProductAction
{
    public function __construct(
        private ProductsRepositoryInterface $repository
    ) {}

    public function execute(CreateProductDTO $dto): Products
    {
        return DB::transaction(function () use ($dto) {
            $data = $dto->toArray();

            if ($dto->hasImage()) {
                $data['image'] = $dto->image->store('products', 'public');
            }

            return $this->repository->create($data);
        });
    }
}