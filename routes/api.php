<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopifyController;
use Illuminate\Http\JsonResponse;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('test', [ShopifyController::class, 'index']);
