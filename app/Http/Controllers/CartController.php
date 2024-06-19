<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowCartRequest;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService
    ) {
    }

    public function show(User $user)
    {
        $cart = $this->cartService->show($user->id);

    }
}
