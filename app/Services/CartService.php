<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\CartStatus;
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
}
