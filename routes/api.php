<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\BrandsApiV1Controller;
use App\Http\Controllers\Api\V1\CartApiV1Controller;
use App\Http\Controllers\Api\V1\CartItemsApiV1Controller;
use App\Http\Controllers\Api\V1\CategoryApiV1Controller;
use App\Http\Controllers\Api\V1\CouponsApiV1Controller;
use App\Http\Controllers\Api\V1\OrdersApiV1Controller; 
use App\Http\Controllers\Api\V1\OrdersItemsApiV1Controller;
use App\Http\Controllers\Api\V1\ProductApiV1Controller; 

use App\Http\Controllers\Api\V2\BrandsApiV2Controller;
use App\Http\Controllers\Api\V2\CartApiV2Controller;
use App\Http\Controllers\Api\V2\CartItemsApiV2Controller;
use App\Http\Controllers\Api\V2\CategoryApiV2Controller;
use App\Http\Controllers\Api\V2\CouponsApiV2Controller;
use App\Http\Controllers\Api\V2\OrdersApiV2Controller; 
use App\Http\Controllers\Api\V2\OrdersItemsApiV2Controller;
use App\Http\Controllers\Api\V2\ProductApiV2Controller; 

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth'])->prefix('V1')->group(function () {
  Route::apiResource('brands', BrandsApiV1Controller::class);
      Route::apiResource('cartitems', CartItemsApiV1Controller::class);
        Route::apiResource('categories',CategoryApiV1Controller::class);
          Route::apiResource('coupons', CouponsApiV1Controller::class);
            Route::apiResource('orders', OrdersApiV1Controller::class);
              Route::apiResource('ordersitems', OrdersItemsApiV1Controller::class);
                Route::apiResource('products', ProductApiV1Controller::class);
                     Route::apiResource('carts',CartApiV1Controller::class);
                
});


Route::middleware(['auth'])->prefix('V2')->group(function () {
    Route::apiResource('brands', BrandsApiV2Controller::class);
      Route::apiResource('cartitems', CartItemsApiV2Controller::class);
        Route::apiResource('categories',CategoryApiV2Controller::class);
          Route::apiResource('coupons', CouponsApiV2Controller::class);
            Route::apiResource('orders', OrdersApiV2Controller::class);
              Route::apiResource('ordersitems', OrdersItemsApiV2Controller::class);
                Route::apiResource('products', ProductApiV2Controller::class);
                    Route::apiResource('carts',CartApiV2Controller::class);
});


Route::middleware(['throttle:api'])->prefix('V1')->group(function (){
         Route::apiResource('brands', BrandsApiV1Controller::class);
            Route::apiResource('cartitems', CartItemsApiV1Controller::class);
                Route::apiResource('categories',CategoryApiV1Controller::class);
                    Route::apiResource('coupons', CouponsApiV1Controller::class);
                        Route::apiResource('orders', OrdersApiV1Controller::class);
                            Route::apiResource('ordersitems', OrdersItemsApiV1Controller::class);
                                Route::apiResource('products', ProductApiV1Controller::class);
                                    Route::apiResource('carts',CartApiV1Controller::class);
});

Route::middleware(['throttle:api'])->prefix('V2')->group(function (){
         Route::apiResource('brands', BrandsApiV2Controller::class);
            Route::apiResource('cartitems', CartItemsApiV2Controller::class);
                Route::apiResource('categories',CategoryApiV2Controller::class);
                    Route::apiResource('coupons', CouponsApiV2Controller::class);
                        Route::apiResource('orders', OrdersApiV2Controller::class);
                            Route::apiResource('ordersitems', OrdersItemsApiV2Controller::class);
                                Route::apiResource('products', ProductApiV2Controller::class);
                                    Route::apiResource('carts',CartApiV2Controller::class);
});