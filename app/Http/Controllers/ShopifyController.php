<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;

class ShopifyController extends Controller
{
    public function __construct(
        private readonly ShopifyService $shopifyService
    ) {
    }

    public function index(): JsonResponse
    {
        return ProductResource::collection(
            $this->shopifyService->fetchProduts()
        )->response();
    }

    public function show(string $handle): JsonResponse
    {
        $product = $this
            ->shopifyService
            ->fetchProductByHandle($handle);

        return ProductResource::make($product)->response();
    }
}
