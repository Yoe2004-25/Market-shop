<?php

namespace App\Observers;

use App\Events\BrandCreated;
use App\Models\Brands;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BrandsObserver
{
    /**
     * Handle the Brands "created" event.
     */
    public function created(Brands $brand): void
    {
        Log::info('Brand created successfully.', [
            'id'     => $brand->id,
            'name'   => $brand->name,
            'logo'   => $brand->logo,
            'user'   => Auth::id(),
        ]);

     
        event(new BrandCreated($brand));
    }

    /**
     * Handle the Brands "updated" event.
     */
    public function updated(Brands $brand): void
    {
        Log::info('Brand updated successfully.', [
            'id'      => $brand->id,
            'name'    => $brand->name,
            'changes' => $brand->getChanges(),
            'user'    => Auth::id(),
        ]);
    }

    
    public function deleted(Brands $brand): void
    {
        Log::warning('Brand soft-deleted.', [
            'id'   => $brand->id,
            'name' => $brand->name,
            'user' => Auth::id(),
        ]);
    }

   
    public function restored(Brands $brand): void
    {
        Log::info('Brand restored.', [
            'id'   => $brand->id,
            'name' => $brand->name,
            'user' => Auth::id(),
        ]);
    }

    /**
     * Handle the Brands "force deleted" event.
     */
    public function forceDeleted(Brands $brand): void
    {
        Log::warning('Brand permanently deleted.', [
            'id'   => $brand->id,
            'name' => $brand->name,
            'user' => Auth::id(),
        ]);
    }
}