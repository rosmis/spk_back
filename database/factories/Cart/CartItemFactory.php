<?php

namespace Database\Factories\Cart;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModelClass of CartItem
 *
 * @extends Factory<TModelClass>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;
    
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function forVariant(int $variantId): self
    {
        return $this->state([
            'product_variant_id' => $variantId,
        ]);
    }

    public function forCart(int $cartId): self
    {
        return $this->state([
            'cart_id' => $cartId,
        ]);
    }

    public function forProductVariant(int $productVariantId): self
    {
        return $this->state([
            'product_variant_id' => $productVariantId,
        ]);
    }
}
