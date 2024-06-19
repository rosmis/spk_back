<?php

namespace App\Http\Controllers;

use App\Dto\Cart\CartItemDto;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Http\Requests\CreateCartRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Resources\Json\JsonResource;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    public function show(User $user): JsonResource
    {
        return CartResource::make(
            $this->cartService->show($user->id)
        );
    }

    /**
     * @throws ActivePendingCartException
     */
    public function store(): JsonResource
    {
        $cart = $this->cartService->store();

        return CartResource::make($cart);
    }

    public function update(Cart $cart, CreateCartRequest $request): JsonResource
    {
        $cart = $this->cartService->update(
            array_map(
                fn (array $item) => CartItemDto::fromArray($item),
                $request->safe()->toArray()
            ),
            $cart
        );

        return CartResource::make($cart);
    }
}
