<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopifyController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::prefix('otp')->group(function () {
    Route::post('verify', [AuthController::class, 'checkOtpValidity']);

    Route::post('forget-password', [AuthController::class, 'forgetPassword']);
    Route::post('password-code', [AuthController::class, 'checkPasswordOtpValidity']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

Route::prefix('webhook')->group(function () {
    Route::post('product', [ShopifyController::class, 'webhookProduct']);
    Route::post('order', [ShopifyController::class, 'webhookOrder']);
});
