<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Cart\CreateCartItemDto;
use App\Enums\CartStatus;
use App\Exceptions\ActivePendingCartException;
use App\Models\Cart;

readonly class CartService
{
    public function show(int $user_id): Cart
    {
        /** @var ?Cart $cart */
        $cart = Cart::query()
            ->where('user_id', $user_id)
            ->where('status', CartStatus::Pending)
            ->with('cartItems')
            ->first();

        return $cart;
    }

    /**
     * @throws ActivePendingCartException
     */
    public function store(): Cart
    {
        /** @var ?Cart $existingPendingCart */
        $existingPendingCart = Cart::query()
            ->where('user_id', auth()->id())
            ->where('status', CartStatus::Pending)
            ->first();

        if ($existingPendingCart instanceof Cart) {
            throw new ActivePendingCartException();
        }

        /** @var Cart $cart */
        $cart = Cart::query()
            ->create([
                'user_id' => auth()->id(),
                'status' => CartStatus::Pending,
            ]);

        return $cart;
    }
}
