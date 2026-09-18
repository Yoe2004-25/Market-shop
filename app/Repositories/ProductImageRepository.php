<?php

namespace App\Repositories;

use App\Http\Controllers\ProductsController;
use App\Models\Product_images;
use App\Models\Product_images as ProductImage;
use Illuminate\Support\Collection;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    
    public function create(array $data): ProductImage
    {
       
      if (isset($data['is_primary']) && $data['is_primary']) 
      {
            $this->removePrimaryFlag($data['product_id']);
      }

        return Product_images::create($data);
    }

  
    public function update(int $id, array $data)
    {
        $image = $this->find($id);
        
        if (!$image) {
            throw new \Exception('الصورة غير موجودة');
        }

      
        if (isset($data['is_primary']) && $data['is_primary']) {
            $this->removePrimaryFlag($data['product_id'] ?? $image->product_id);
        }

        $image->update($data);
        return $image->fresh();
    }

 
    public function delete(int $id): bool
    {
        $image = $this->find($id);
        return $image ? $image->delete() : false;
    }

  
    public function find(int $id)
    {
        return Product_images::find($id);
    }

   
    public function getByProduct(int $productId): Collection
    {
        return Product_images::where('product_id', $productId)
                          ->orderBy('is_primary', 'desc')
                          ->get();
    }

    
    public function getPrimaryImage(int $productId)
    {
        return Product_images::where('product_id', $productId)
                          ->where('is_primary', true)
                          ->first();
    }

    
    public function removePrimaryFlag(int $productId): void
    {
      Product_images::where('product_id', $productId)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
    }
}