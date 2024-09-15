<?php

declare(strict_types=1);

namespace App\Dto\Cart;

final class CartItemDto
{
    public function __construct(
        public int $quantity,
        public int | string $variantId,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            quantity: $data['quantity'],
            variantId: $data['variantId'],
        );
    }
}
