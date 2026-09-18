<?php

namespace App\Providers;

use App\Models\Brands;
use App\Models\Cart;
use App\Models\Cart_items;
use App\Models\Categories;
use App\Models\Coupons;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Product_images;
use App\Models\Products;
use App\Models\Reviews;
use App\Models\wishlist;

use App\Policies\BrandsPolicy;
use App\Policies\CartItemsPolicy;
use App\Policies\CartPolicy;
use App\Policies\CategoriesPolicy;
use App\Policies\CouponsPolicy;
use App\Policies\OrdersItemsPolicy;
use App\Policies\OrdersPolicy;
use App\Policies\ProductImagesPolicy;
use App\Policies\ProductsPolicy;
use App\Policies\ReviewsPolicy;
use App\Policies\WishlistPolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Brands::class => BrandsPolicy::class,
        Cart::class=> CartPolicy::class,
        Cart_items::class=> CartItemsPolicy::class,
        Categories::class=> CategoriesPolicy::class,
        Coupons::class=> CouponsPolicy::class,
        Orders::class=> OrdersPolicy::class,
        OrdersItems::class=> OrdersItemsPolicy::class,
        Product_images::class=> ProductImagesPolicy::class,
        Products::class=> ProductsPolicy::class,
        Reviews::class=> ReviewsPolicy::class,
        wishlist::class=> WishlistPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}