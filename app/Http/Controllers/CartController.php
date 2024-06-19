<?php

namespace App\Http\Controllers;

use App\Dto\Cart\CreateCartItemDto;
use App\Exceptions\ActivePendingCartException;
use App\Http\Requests\CreateCartRequest;
use App\Http\Resources\Cart\CartResource;
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

    /*public function store(CreateCartRequest $request): JsonResource
    {
        $cart = $this->cartService->store(
            array_map(
                fn (array $item) => CreateCartItemDto::fromArray($item),
                $request->safe()->toArray()
            )
        );

        return CartResource::make($cart);
    }*/
}
