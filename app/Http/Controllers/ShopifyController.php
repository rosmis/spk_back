<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidWebhookSignatureException;
use App\Http\Resources\ProductResource;
use App\Jobs\ProcessWebhookProductJob;
use App\Traits\ValidateWebhookSignature;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopifyController extends Controller
{
    use ValidateWebhookSignature;

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
        if (! $this->validateWebhookSignature($request)) {
            throw new InvalidWebhookSignatureException();
        }

        return response()->json(['message' => 'Webhook received'])->setStatusCode(200);
        ProcessWebhookProductJob::dispatch($request->all());

        return response()->json(['message' => 'Webhook received']);
    }
}
