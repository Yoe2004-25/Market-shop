<?php

namespace App\Observers;

use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CategoriesObserver
{
    /**
     * Handle the Categorires "created" event.
     * 
     * 
        'name', 
        'slug',
        'description',
        'image' , 
        'status' , 
     */
    public function created(Categories $categories): void
    {
        Log::info('Successfully created a category.', [
            'slug' => $categories->slug,
            'name'=>$categories->name , 
            'user'=>Auth()->id() ,
        ]);

        Log::info('new Catrgories id added' .$categories->name) ; 
    }

    /**
     * Handle the Categorires "updated" event.
     */
    public function updated(Categories $categories): void
    {
        Log::info( 'Successfullty a updated to cateergory' , [
             'slug' => $categories->slug,
            'name'=>$categories->name , 
        ]);

          Log::info('new Catrgories id added' .$categories->name) ; 
    }

    /**
     * Handle the Categorires "deleted" event.
     */
    public function deleted(Categories $categories): void
    {
         Log::info( 'Successfullty a Deleted to categories', [
             'slug' => $categories->slug,
            'name'=>$categories->name , 
        ]);

          Log::info('new Catrgories id added' .$categories->name) ; 
    }

    /**
     * Handle the Categorires "restored" event.
     */
    public function restored(Categories $categories): void
    {
         Log::info( 'Successfullty a restored to catergory' , [
             'slug' => $categories->slug,
            'name'=>$categories->name , 
        ]);

          Log::info(' the category is restotred ' .$categories->name) ; 
    }

    /**
     * Handle the Categorires "force deleted" event.
     */
    public function forceDeleted(Categories $categories): void
    {
         Log::info( 'Successfullty a force deelted  to cateergory' , [
             'slug' => $categories->slug,
            'name'=>$categories->name , 
        ]);

          Log::info('deleted the Category' .$categories->name) ; 
    }
}