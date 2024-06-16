<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Jobs\ProcessWebhookProductJob;
use App\Services\ShopifyService;
use Illuminate\Http\Client\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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

    public function webhook(Request $request): JsonResponse
    {
        Log::info('Webhook received', $request->all());
        return response()->json(['message' => 'Webhook received']);

        ProcessWebhookProductJob::dispatch($request->all());

        return response()->json(['message' => 'Webhook received']);
    }
}
