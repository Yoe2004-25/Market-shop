<?php

namespace App\Actions\Categories;

use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteCategoryAction
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function execute(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $category = $this->categoryRepository->findById($id);

            if (!$category) {
                return false;
            }

            if ($category->products()->count() > 0) {
                throw new \Exception('Cannot delete category with existing products.');
            }

           
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            return $this->categoryRepository->delete($id);   
        });
    }
}