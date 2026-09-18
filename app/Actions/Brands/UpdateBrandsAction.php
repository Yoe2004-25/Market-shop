<?php

namespace App\Actions\Brands;

use App\DTOs\Brands\UpdateBrandsDTO;
use App\Models\Brands;
use App\Repositories\BrandsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class UpdateBrandsAction
{
    public function __construct(
        private BrandsRepositoryInterface $repository
    ) {}

    public function execute(Brands $model, UpdateBrandsDTO $dto): Brands
    {
        $data = $dto->toArray();

     
        if ($dto->logo) {
            if ($model->logo && Storage::disk('public')->exists($model->logo)) {
                Storage::disk('public')->delete($model->logo);
            }
            $data['logo'] = $dto->logo->store('brands', 'public');
        }

        return $this->repository->update($model, $data);
    }
}