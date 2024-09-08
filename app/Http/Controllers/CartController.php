<?php

namespace App\Http\Controllers;

use App\Dto\Cart\CartItemDto;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Exceptions\Cart\BadStatusCartException;
use App\Exceptions\Cart\IncorrectUserIdCart;
use App\Http\Requests\CreateCartRequest;
use App\Http\Resources\Cart\CartResource;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

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
    public function update(Cart $cart, CreateCartRequest $request): JsonResource
    {
        $cart = $this->cartService->update(
            array_map(
                fn (array $item) => CartItemDto::fromArray($item),
                $request->safe()->toArray()
            ),
            $cart,
            $request->user()
        );

        return CartResource::make($cart);
    }

//    public function getCartChekoutUrl(Cart $cart): JsonResponse
//    {
//        $cartCheckoutUrl = $this->cartService->getCartCheckoutUrl($cart);
//
//        return new JsonResponse([
//            'checkout_url' => $cartCheckoutUrl
//        ]);
//    }
}
