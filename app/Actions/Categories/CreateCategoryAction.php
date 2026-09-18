<?php

namespace App\Actions\Categories;

use App\DTOs\Category\CategoryDTO;
use App\Models\Categories;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateCategoryAction
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(CategoryDTO $dto): Categories
    {
        return DB::transaction(function () use ($dto) {
            $category = $this->categoryRepository->create($dto->toArray());

            if ($dto->hasImage()) {
                $path = $dto->image->store('categories', 'public');
                $category->update(['image' => $path]);
            }

            return $category->loadCount('products');
        });
    }
}