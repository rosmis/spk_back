<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use Illuminate\Http\JsonResponse;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*Route::prefix('products')->group(function () {

});*/

Route::get('/products', [ShopifyController::class, 'index']);
Route::get('/products/{handle}', [ShopifyController::class, 'show']);
