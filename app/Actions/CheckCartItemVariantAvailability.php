<?php

declare(strict_types=1);

namespace App\Actions;

use App\Dto\Cart\CartItemDto;
use App\Exceptions\Cart\OutOfStockCartVariantException;
use App\Exceptions\Cart\QuantityCartVariantException;
use App\Models\ProductVariant;

/**
 * @throws QuantityCartVariantException
 * @throws OutOfStockCartVariantException
 */
final class CheckCartItemVariantAvailability
{
    public function __invoke(CartItemDto $cartItem): void
    {
        /** @var ProductVariant $selectedProductVariant */
        $selectedProductVariant = ProductVariant::query()
            ->where('id', $cartItem->variantId)
            ->firstOrFail();

        if ($selectedProductVariant->quantity_available == 0) {
            throw new OutOfStockCartVariantException;
        }

        if ($selectedProductVariant->quantity_available < $cartItem->quantity) {
            throw new QuantityCartVariantException;
        }
    }
}
