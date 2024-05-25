<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\JsonResponse;

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
