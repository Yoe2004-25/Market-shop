<?php

namespace App\Actions\Products;

use App\Models\Products;
use App\Repositories\ProductsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class DeleteProductAction
{
    public function __construct(private ProductsRepositoryInterface $repository) {}

    public function execute(Products $model): bool
    {
        if ($model->image && Storage::disk('public')->exists($model->image)) {
            Storage::disk('public')->delete($model->image);
        }

        return $this->repository->delete($model);
    }
}