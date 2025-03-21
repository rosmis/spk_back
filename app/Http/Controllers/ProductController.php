<?php

namespace App\Http\Controllers;

use App\Http\Resources\Product\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index(): JsonResponse
    {
        return ProductResource::collection(
            $this->productService->list()
        )->response();
    }

//    public function show(string $handle): JsonResponse
//    {
//        $product = $this
//            ->productService
//            ->show($handle);
//
//        return ProductResource::make($product)->response();
//    }
}
