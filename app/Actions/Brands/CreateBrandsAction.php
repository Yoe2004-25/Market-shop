<?php

namespace App\Actions\Brands;

use App\DTOs\Brands\CreateBrandsDTO;
use App\Models\Brands;
use App\Repositories\BrandsRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class CreateBrandsAction
{
    public function __construct(
        private BrandsRepositoryInterface $repository
    ) {}

    public function execute(CreateBrandsDTO $dto): Brands
    {
        $data = $dto->toArray();

   
        if ($dto->logo) {
            $data['logo'] = $dto->logo->store('brands', 'public');
        }

        return $this->repository->create($data);
    }
}