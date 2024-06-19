<?php

declare(strict_types=1);

namespace App\Dto\Cart;

final class CreateCartItemDto
{
    public function __construct(
        public int $productId,
        public int $quantity,
        public int $variantId,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            productId: $data['product_id'],
            quantity: $data['quantity'],
            variantId: $data['variant_id'],
        );
    }
}
