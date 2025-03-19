<?php

namespace App\Http\Controllers;

use App\Dto\Cart\CartItemDto;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Exceptions\Cart\BadStatusCartException;
use App\Exceptions\Cart\IncorrectUserIdCart;
use App\Http\Requests\StoreCartItemRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Shopify\Exception\MissingArgumentException;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {}

    public function index(Request $request): JsonResource
    {
        return CartResource::make(
            $this->cartService->show(
                $request->user()
            )
        );
    }

    /**
     * @throws ActivePendingCartException
     */
    public function store(Request $request): JsonResource
    {
        $cart = $this->cartService->store(
            $request->user()
        );

        return CartResource::make($cart);
    }

    /**
     * @throws BadStatusCartException
     * @throws IncorrectUserIdCart
     */
    public function update(StoreCartItemRequest $request, Cart $cart): JsonResource
    {
        $cart = $this->cartService->update(
            CartItemDto::fromArray($request->safe()->toArray()),
            $cart,
            $request->user()
        );

        return CartResource::make($cart);
    }
    public function destroy(Cart $cart, int $cartItemId): JsonResponse
    {
        $this->cartService->destroy($cart, $cartItemId);

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * @throws IncorrectUserIdCart
     * @throws MissingArgumentException
     */
    public function getCartCheckoutUrl(Cart $cart, Request $request): JsonResponse
    {
        $cartCheckoutUrl = $this->cartService->getCartCheckoutUrl(
            $cart,
            $request->user()
        );

        return new JsonResponse([
            'checkout_url' => $cartCheckoutUrl,
        ]);
    }

    /**
     * @throws MissingArgumentException
     */
    public function getMobileCheckoutUrl(StoreCartItemRequest $request): JsonResponse
    {

        /** @var ProductVariant $selectedProductVariant */
        $selectedProductVariant = ProductVariant::query()
            ->findOrFail($request->integer('variantId'));

        $cartCheckoutUrl = $this->cartService->getMobileCheckoutUrl(
            CartItemDto::fromArray(
                array_merge(
                    $request->safe()->toArray(),
                    ['shopifyGid' => $selectedProductVariant->shopify_gid]
                )
            )
        );

        return new JsonResponse([
            'checkout_url' => $cartCheckoutUrl,
        ]);
    }
}
