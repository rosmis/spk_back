<?php

declare(strict_types=1);

namespace App\Services;

use App\Actions\CheckCartItemVariantAvailability;
use App\Dto\Cart\CartItemDto;
use App\Enums\CartStatus;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Exceptions\Cart\BadStatusCartException;
use App\Exceptions\Cart\IncorrectUserIdCart;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Shopify\Exception\MissingArgumentException;

readonly class CartService
{
    public function __construct(
        public ShopifyService $shopifyService
    ) {}

    public function show(User $user): ?Cart
    {
        /** @var ?Cart $cart */
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', CartStatus::Pending)
            ->with('cartItems.productVariant')
            ->first();

        return $cart;
    }

    /**
     * @throws ActivePendingCartException
     */
    public function store(User $user): Cart
    {
        /** @var ?Cart $existingPendingCart */
        $existingPendingCart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', CartStatus::Pending)
            ->first();

        if ($existingPendingCart instanceof Cart) {
            throw new ActivePendingCartException;
        }

        /** @var Cart $cart */
        $cart = Cart::query()
            ->create([
                'user_id' => $user->id,
                'status' => CartStatus::Pending,
            ]);

        return $cart;
    }

    /**
     * @throws BadStatusCartException|IncorrectUserIdCart
     */
    public function update(CartItemDto $cartItem, Cart $cart, User $user): Cart
    {
        App::call(CheckCartItemVariantAvailability::class, ['cartItem' => $cartItem]);

        if ($cart->status !== CartStatus::Pending) {
            throw new BadStatusCartException;
        }

        if ($user->id !== $cart->user_id) {
            throw new IncorrectUserIdCart;
        }

        $cart->cartItems()
            ->updateOrCreate([
                'product_variant_id' => $cartItem->variantId,
            ], [
                'quantity' => $cartItem->quantity,
                'image_url' => $cartItem->imageUrl,
            ]);

        $cart->load('cartItems.productVariant');

        return $cart;
    }

    public function destroy(Cart $cart, int $cartItemId): void
    {
        $cart->cartItems()->where('id', $cartItemId)->delete();
    }

    /**
     * @throws MissingArgumentException
     * @throws BadStatusCartException
     * @throws IncorrectUserIdCart
     */
    public function getCartCheckoutUrl(Cart $cart, User $user): string
    {
        if ($user->id !== $cart->user_id) {
            throw new IncorrectUserIdCart;
        }

        if ($cart->status !== CartStatus::Pending) {
            throw new BadStatusCartException;
        }

        if ($cart->cartItems->isEmpty()) {
            throw new MissingArgumentException('Cart items are empty');
        }

        /** @var Collection<int,CartItemDto> $cartiItemsDto */
        $cartItemsDto = $cart->cartItems
            ->map(
                static fn (CartItem $cartItem): CartItemDto => new CartItemDto(
                    quantity: $cartItem->quantity,
                    variantId: $cartItem->product_variant_id,
                    imageUrl: $cartItem->image_url,
                    shopifyGid: $cartItem->productVariant->shopify_gid,
                )
            );

        // Check cart Item availability
        $cartItemsDto->each(
            static fn (CartItemDto $cartItemDto) => App::call(
                CheckCartItemVariantAvailability::class,
                ['cartItem' => $cartItemDto]
            )
        );

        return $this->shopifyService->generateCartCheckoutUrl($cartItemsDto, $user);
    }

    /**
     * @throws MissingArgumentException
     */
    public function getMobileCheckoutUrl(CartItemDto $cartItem): string
    {
        // Check cart Item availability
        App::call(CheckCartItemVariantAvailability::class, ['cartItem' => $cartItem]);

        return $this->shopifyService->generateCartCheckoutUrl(
            Collection::make([$cartItem])
        );

    }
}
