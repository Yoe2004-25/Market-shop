<?php

use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CouponsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrdersItemsController;
use App\Http\Controllers\ProductImagesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartItemsController;
use App\Http\Controllers\PaymentsController;  
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function (){
        Route::resource('carts',CartController::class);  
        Route::resource('coupons',CouponsController::class); 
        Route::resource('orders',OrdersController::class);  
        Route::resource('products',ProductsController::class); 
        Route::resource('productsimages',ProductImagesController::class); 
        Route::resource('reviews',ReviewsController::class); 
        Route::resource('wishlist',WishlistController::class);
        Route::resource('cartitems',CartItemsController::class);
        Route::resource('brands',BrandsController::class); 
        Route::resource('categories',CategoriesController::class); 
        Route::resource('brands',BrandsController::class);
        Route::resource('categories',CategoriesController::class);
});



Route::middleware('auth')->prefix('payments')->name('payments.')->group(function () {
    Route::get('/',[PaymentsController::class, 'index'])->name('index');
    Route::get('/create',[PaymentsController::class, 'create'])->name('create');
    Route::post('/',[PaymentsController::class, 'store'])->name('store');
    Route::get('/{payment}',[PaymentsController::class, 'show'])->name('show');
    Route::get('/{payment}/edit',[PaymentsController::class, 'edit'])->name('edit');
    Route::put('/{payment}',[PaymentsController::class, 'update'])->name('update');
    Route::delete('/{payment}',[PaymentsController::class, 'destroy'])->name('destroy');
    Route::post('/{payment}/confirm-cash',[PaymentsController::class, 'confirmCash'])->name('confirm-cash');
    Route::post('/{payment}/refund',[PaymentsController::class, 'refund'])->name('refund');
});

require __DIR__.'/auth.php';