<?php

namespace App\Observers;

use App\Models\Products;
use Illuminate\Support\Facades\Log;
use App\Events\ProductCreated;
class ProductObserver
{
    /**
     * 
     * 
     * 
     * 
         'categery_id', 
        'brand_id',
            'name', 
            'slug',
            'description', 
            'price',
            'discount', 
            'stock',
            'sku',
            'image', 
            'status', 
     * Handle the Product "created" event.
     */
    public function created(Products $product , ProductCreated $event): void
    {
         Log::info('Product created', [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'stock' => $product->stock,
            'category_id' => $product->category_id,
            'brand_id' => $product->brand_id,
            'user_id' => auth()->id(),
        ]);

        
        Log::info('we created a new product'. $product->name); 
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Products $product): void
    {
        Log::info('Product updated', [
            'id' => $product->id,
            'changes' => $product->getChanges(),
            'user_id' => auth()->id(),
        ]);

        Log::info('we updated product'. $product->name); 
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Products $product): void
    {
        Log::warning('Product deleted', [
            'id' => $product->id,
            'name' => $product->name,
            'user_id' => auth()->id(),
        ]);

        Log::info('we deleted  product'. $product->name); 
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Products $product): void
    {
        Log::info('Product restored', ['id' => $product->id]);

        Log::info('we restored product'. $product->name); 
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Products $product): void
    {
        Log::warning('Product force deleted', ['id' => $product->id]);
    }
}