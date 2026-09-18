<?php

namespace App\Actions\Products;

use App\DTOs\Products\UpdateProductDTO;
use App\Models\Products;
use App\Repositories\ProductsRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateProductAction
{
    public function __construct(
        private ProductsRepositoryInterface $repository
    ) {}

    public function execute(Products $model, UpdateProductDTO $dto): Products
    {
        return DB::transaction(function () use ($model, $dto) {
            $data = $dto->toArray();

            if ($dto->hasImage()) {
                // امسح الصورة القديمة
                if ($model->image && Storage::disk('public')->exists($model->image)) {
                    Storage::disk('public')->delete($model->image);
                }

                $data['image'] = $dto->image->store('products', 'public');
            }

            return $this->repository->update($model, $data);
        });
    }
}