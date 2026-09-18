<?php


namespace App\Providers;

use App\Repositories\CartItemRepository;
use App\Repositories\CartItemRepositoryInterface;
use App\Repositories\CartRepository;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\ReviewRepository;
use App\Repositories\ReviewRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\OrdersItemsRepository;
use App\Repositories\OrdersItemsRepositoryInterface;
use App\Repositories\CouponsRepositoryInterface;
use App\Repositories\CouponsRepository;
use App\Repositories\BrandsRepository;
use App\Repositories\BrandsRepositoryInterface;
use App\Repositories\OrdersRepository;
use App\Repositories\OrdersRepositoryInterface;
use App\Repositories\ProductsRepository; 
use App\Repositories\ProductsRepositoryInterface ;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
       $this->app->bind(CategoryRepositoryInterface::class,CategoryRepository::class);
        $this->app->bind(BrandsRepositoryInterface::class, BrandsRepository::class);
        $this->app->bind(OrdersItemsRepositoryInterface::class, OrdersItemsRepository::class);
        $this->app->bind(CouponsRepositoryInterface::class, CouponsRepository::class);
        $this->app->bind(OrdersRepositoryInterface::class,OrdersRepository::class);
        $this->app->bind(CartItemRepositoryInterface::class,CartItemRepository::class);
        $this->app->bind(CartRepositoryInterface::class,CartRepository::class);
        $this->app->bind(ProductsRepositoryInterface::class,ProductsRepository::class); 
        $this->app->bind(ReviewRepositoryInterface::class,ReviewRepository::class); 
    }

    public function boot(): void
    {
       
    }
}