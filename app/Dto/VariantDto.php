<?php

declare(strict_types=1);

namespace App\Dto;

final class VariantDto
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
            id: $data['node']['id'],
            title: $data['node']['title'],
            quantityAvailable: (int) $data['node']['quantityAvailable'],
            price: $data['node']['price'],
        );
    }
}
