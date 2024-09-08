<?php

declare(strict_types=1);

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('products')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::get('{handle}', [ProductController::class, 'show']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('', [CartController::class, 'index']);
        Route::post('', [CartController::class, 'store']);
        Route::patch('{cart}', [CartController::class, 'update'])
            ->whereNumber('cart');
    });
});
