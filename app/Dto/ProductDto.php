<?php

declare(strict_types=1);

namespace App\Dto;

final class ProductDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public array $variants,
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
            description: $data['description'],
            variants: array_map(
                fn (array $variant) => VariantDto::fromArray($variant),
                $data['variants']['edges'],
            ));
    }
}
