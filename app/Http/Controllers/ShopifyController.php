<?php

namespace App\Http\Controllers;

use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    public function __construct(
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $shopifyApi = app(ShopifyService::class);

        dd($shopifyApi->fetchProduts());

        return response()->json([
            'message' => 'Hello from ShopifyController',
        ]);
    }
}
