<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\Webhook\WebhookOrderDto;
use App\Dto\Webhook\WebhookProductDto;
use App\Exceptions\InvalidWebhookSignatureException;
use App\Http\Requests\WebhookOrderRequest;
use App\Http\Requests\WebhookProductRequest;
use App\Http\Resources\Shopify\ShopifyProductResource;
use App\Jobs\ProcessWebhookOrderJob;
use App\Jobs\ProcessWebhookProductJob;
use App\Services\ShopifyService;
use App\Traits\ValidateWebhookSignature;
use Illuminate\Http\JsonResponse;

class ShopifyController extends Controller
{
    use ValidateWebhookSignature;

    public function __construct(
        private readonly ShopifyService $shopifyService
    ) {}

    public function index(): JsonResponse
    {
        return ShopifyProductResource::collection(
            $this->shopifyService->fetchProduts()
        )->response();
    }

    public function show(string $handle): JsonResponse
    {
        $product = $this
            ->shopifyService
            ->fetchProductByHandle($handle);

        return ShopifyProductResource::make($product)->response();
    }

    /**
     * @throws InvalidWebhookSignatureException
     */
    public function webhookProduct(WebhookProductRequest $request): JsonResponse
    {
        if (! $this->validateWebhookSignature($request)) {
            throw new InvalidWebhookSignatureException;
        }

        ProcessWebhookProductJob::dispatch(
            WebhookProductDto::fromArray($request->safe()->toArray())
        );

        return response()->json(['message' => 'Webhook received']);
    }

    /**
     * @throws InvalidWebhookSignatureException
     */
    public function webhookOrder(WebhookOrderRequest $request): JsonResponse
    {
        if (! $this->validateWebhookSignature($request)) {
            throw new InvalidWebhookSignatureException;
        }

        ProcessWebhookOrderJob::dispatch(
            WebhookOrderDto::fromArray($request->safe()->toArray())
        );

        return response()->json(['message' => 'Webhook received']);
    }
}
