<?php


namespace App\Providers;

use App\Models\Orders;
use App\Models\Brands; 
use App\Models\Cart; 
use App\Models\Cart_items; 
use App\Models\Categories; 
use App\Models\Products; 
use App\Models\Reviews;
use App\Observers\OrdersObserver;
use App\Observers\BrandsObserver; 
use App\Observers\CartItemsObserver; 
use App\Observers\CategoriesObserver; 
use App\Observers\ObserverCart; 
use App\Observers\ProductObserver;
use App\Observers\ReviewsObserver ; 

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Orders::observe(OrdersObserver::class);
        Cart_items::observe(CartItemsObserver::class); 
        Products::observe(ProductObserver::class);
        Brands::observe(BrandsObserver::class); 
        Categories::observe(CategoriesObserver::class); 
        Cart::observe(ObserverCart::class); 
        Reviews::observe(ReviewsObserver::class);
    }
}