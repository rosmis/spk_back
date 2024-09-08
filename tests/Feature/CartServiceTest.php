<?php

namespace Tests\Feature;

use App\Dto\Cart\CartItemDto;
use App\Enums\CartStatus;
use App\Exceptions\Cart\ActivePendingCartException;
use App\Exceptions\Cart\BadStatusCartException;
use App\Exceptions\Cart\IncorrectUserIdCart;
use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\CartService;
use Database\Factories\Cart\CartFactory;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductVariantFactory;
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

    #[TestDox(
        'Given a user and no pending cart,
        When the user requests the cart,
        Then it should return null.'
    )]
    public function testShouldReturnNull(): void
    {
        $this->cart->delete();

        $activeCart = $this->cartService->show($this->user);

        $this->assertNull($activeCart);
    }

    #[TestDox(
        'Given a user and no pending cart,
        When the user creates a cart,
        Then it should create a new pending cart.'
    )]
    public function testShouldCreateNewPendingCart(): void
    {
        $this->cart->delete();

        $newCart = $this->cartService->store($this->user);

        $this->assertInstanceOf(Cart::class, $newCart);
        $this->assertEquals($this->user->id, $newCart->user_id);
        $this->assertEquals(CartStatus::Pending, $newCart->status);
    }

    #[TestDox(
        'Given a user and an existing pending cart,
        When the user creates a cart,
        Then it should throw ActivePendingCartException.'
    )]
    public function testShouldThrowActivePendingCartException(): void
    {
        $this->expectException(ActivePendingCartException::class);

        $this->cartService->store($this->user);

        $this->assertDatabaseCount('carts', 1);
    }

    #[TestDox(
        'Given a user and a pending cart,
        When the user updates the cart,
        Then it should update the cart.'
    )]
    public function testShouldUpdateCart(): void
    {
        $product = ProductFactory::new()->createOne();

        /** @var ProductVariant $productVariant */
        $productVariant = ProductVariantFactory::new()
            ->forProduct($product->id)
            ->createOne();

        $cartItemDto = new CartItemDto(
            productId: $product->id,
            quantity: 2,
            variantId: $productVariant->id,
        );

        $updatedCart = $this->cartService->update(
            [$cartItemDto],
            $this->cart,
            $this->user
        );

        $this->assertInstanceOf(Cart::class, $updatedCart);
        $this->assertEquals($this->cart->id, $updatedCart->id);
        $this->assertEquals($this->cart->status, $updatedCart->status);
        $this->assertContains(
            $cartItemDto->variantId,
            $updatedCart->cartItems->pluck('product_variant_id')
        );
    }

    #[TestDox(
        'Given a user and a non-pending cart,
        When the user updates the cart,
        Then it should throw BadStatusCartException.'
    )]
    public function testShouldThrowBadStatusCartException(): void
    {
        $this->cart->update(['status' => CartStatus::Completed]);

        $this->expectException(BadStatusCartException::class);

        $this->cartService->update([], $this->cart, $this->user);
    }

    #[TestDox(
        'Given a user and a pending cart,
        When the user updates the cart with a different user,
        Then it should throw IncorrectUserIdCart.'
    )]
    public function testShouldThrowIncorrectUserIdCart(): void
    {
        $otherUser = UserFactory::new()->createOne();

        $this->expectException(IncorrectUserIdCart::class);

        $this->cartService->update([], $this->cart, $otherUser);
    }
}