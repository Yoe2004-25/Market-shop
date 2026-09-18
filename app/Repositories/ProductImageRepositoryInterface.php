<?php

namespace App\Repositories ;

use App\Models\Product_images;
use Illuminate\Support\Collection;

interface ProductImageRepositoryInterface
{
    public function create(array $data): ProductImage;
    public function update(int $id, array $data): ProductImage;
    public function delete(int $id): bool;
    public function find(int $id): ?ProductImage;
    public function getByProduct(int $productId): Collection;
    public function getPrimaryImage(int $productId): ?ProductImage;
    public function removePrimaryFlag(int $productId): void;
}