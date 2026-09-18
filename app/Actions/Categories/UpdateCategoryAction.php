<?php

namespace App\Actions\Categories;

use App\DTOs\Category\CategoryDTO;
use App\Models\Categories;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateCategoryAction
{
    public function __construct( protected CategoryRepositoryInterface $categoryRepository ) {}

    public function execute(int $id, CategoryDTO $dto): Categories
    {
        return DB::transaction(function () use ($id, $dto) {
            $category = $this->categoryRepository->update($id, $dto->toArray());

            if ($dto->hasImage()) {
              
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

               
                $path = $dto->image->store('categories', 'public');
                $category->update(['image' => $path]);
            }

            return $category->fresh()->loadCount('products');
        });
    }
}