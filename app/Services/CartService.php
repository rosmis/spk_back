<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Cart\CartItemDto;
use App\Enums\CartStatus;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Exceptions\Cart\BadStatusCartException;
use App\Exceptions\Cart\IncorrectUserIdCart;
use App\Models\Cart;
use App\Models\User;

readonly class CartService
{
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
            throw new ActivePendingCartException();
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
     * @param array<int,CartItemDto> $cartItems
     * @throws BadStatusCartException|IncorrectUserIdCart
     */
    public function update(array $cartItems, Cart $cart, User $user): Cart
    {
        if ($cart->status !== CartStatus::Pending) {
            throw new BadStatusCartException();
        }

        if ($user->id !== $cart->user_id) {
            throw new IncorrectUserIdCart();
        }

        foreach ($cartItems as $cartItem) {
            $cart->cartItems()->updateOrCreate([
                'product_variant_id' => $cartItem->variantId,
            ], [
                'quantity' => $cartItem->quantity,
            ]);
        }

        return $cart;
    }
}
