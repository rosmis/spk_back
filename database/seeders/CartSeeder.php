<?php

namespace Database\Seeders;

use App\Models\ProductVariant;
use Database\Factories\Cart\CartFactory;
use Database\Factories\Cart\CartItemFactory;
use Database\Factories\Product\ProductFactory;
use Database\Factories\Product\ProductVariantFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $user = UserFactory::new()->createOne();

        $cart = CartFactory::new()
            ->forUser($user->id)
            ->createOne();

        $product = ProductFactory::new()->createOne();

        ProductVariantFactory::new()
            ->forProduct($product->id)
            ->create(2)
            ->each(function (ProductVariant $productVariant) use ($cart) {
                CartItemFactory::new()
                    ->forCart($cart->id)
                    ->forProductVariant($productVariant->id)
                    ->create();
            });
    }
}
