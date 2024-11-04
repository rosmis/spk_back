<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('otp')->group(function () {
    Route::post('verify', [AuthController::class, 'checkOtpValidity']);
    Route::post('resend', [AuthController::class, 'resendOtp']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('products')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::get('{handle}', [ProductController::class, 'show']);
    });

    Route::prefix('cart')->group(function () {
        Route::post('{cart}/checkout-url', [CartController::class, 'getCartCheckoutUrl'])
            ->whereNumber('cart');
        Route::get('', [CartController::class, 'index']);
        Route::post('', [CartController::class, 'store']);
        Route::put('{cart}', [CartController::class, 'update'])
            ->whereNumber('cart');
        Route::delete('{cart}/cart-item/{cartItemId}', [CartController::class, 'destroy']);
    });

    //    Route::prefix('shopify')->group(function () {
    //        Route::get('{handle}', [ShopifyController::class, 'show']);
    //    });
});
