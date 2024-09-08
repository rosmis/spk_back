<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Dto\ProductDto;
use App\Exceptions\InvalidWebhookSignatureException;
use App\Http\Requests\WebhookProductRequest;
use App\Http\Resources\Shopify\ShopifyProductResource;
use App\Jobs\ProcessWebhookProductJob;
use App\Services\ShopifyService;
use App\Traits\ValidateWebhookSignature;
use Illuminate\Http\JsonResponse;

class ShopifyController extends Controller
{
    use ValidateWebhookSignature;

    public function __construct(
        private readonly ShopifyService $shopifyService
    ) {
    }

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

    /*public function setCartItems(array $products): JsonResponse
    {
        $cartItems = $this->shopifyService->setCartItems();
        return response()->json($cartItems);
    }*/

    public function webhook(WebhookProductRequest $request): JsonResponse
    {
        if (! $this->validateWebhookSignature($request)) {
            throw new InvalidWebhookSignatureException();
        }

        ProcessWebhookProductJob::dispatch(
            ProductDto::fromArray($request->safe()->toArray())
        );

        return response()->json(['message' => 'Webhook received']);
    }
}
