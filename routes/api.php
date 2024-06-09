<?php

use App\Http\Controllers\ShopifyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('products')->group(function () {
    Route::get('', [ShopifyController::class, 'index']);
    Route::get('{handle}', [ShopifyController::class, 'show']);
});
