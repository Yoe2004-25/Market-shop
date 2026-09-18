<?php

namespace App\Actions\Brands;

use App\Models\Brands;
use App\Repositories\BrandsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class DeleteBrandsAction
{
    public function __construct(
        private BrandsRepositoryInterface $repository
    ) {}

    public function execute(Brands $model): bool
    {
     
        if ($model->logo && Storage::disk('public')->exists($model->logo)) {
            Storage::disk('public')->delete($model->logo);
        }

        return $this->repository->delete($model);
    }
}