<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;

class ShopifyController extends Controller
{
    public function __construct(
    ) {
    }

    public function index(): JsonResponse
    {
        /** @var ShopifyService $shopifyApi */
        $shopifyApi = app(ShopifyService::class);

        return ProductResource::collection(
            $shopifyApi->fetchProduts()
        )->response();
    }

    public function show(string $handle): JsonResponse
    {
        /** @var ShopifyService $shopifyApi */
        $shopifyApi = app(ShopifyService::class);

        $product = $shopifyApi->fetchProductByHandle($handle);

        return ProductResource::make($product)->response();
    }
}
