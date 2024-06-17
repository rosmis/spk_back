<?php

declare(strict_types=1);

namespace App\Dto;

final class ProductVariantDto
{
    public function __construct(
        public string $id,
        public string $title,
        public int $quantityAvailable,
        public array $price,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            title: $data['title'],
            quantityAvailable: (int) $data['inventory_quantity'],
            price: $data['price'],
        );
    }
}
