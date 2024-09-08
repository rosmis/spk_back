<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use Database\Factories\Cart\CartFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use DatabaseMigrations;
    private CartService $cartService;
    private Cart $cart;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = app()->make(CartService::class);

        $this->user = UserFactory::new()->createOne();

        $this->cart = CartFactory::new()
            ->forUser($this->user->id)
            ->withCartStatusPending()
            ->createOne();
    }

    #[TestDox(
        'Given a user and a pending cart,
        When the user requests the cart,
        Then it should return the pending cart.'
    )]
    public function testShouldReturnPendingCart(): void
    {
        $activeCart = $this->cartService->show($this->user);

        $this->assertInstanceOf(Cart::class, $activeCart);
        $this->assertEquals($this->cart->id, $activeCart->id);
        $this->assertEquals($this->cart->status, $activeCart->status);
    }
}
