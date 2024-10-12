<?php

declare(strict_types=1);

namespace App\Dto;

final class ProductDto
{
    /**
     * @param  array<int,ProductVariantDto>  $variants
     * @param  array<int,ProductImageDto>  $images
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $handle,
        public string $description,
        public array $variants,
        public array $images
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
            handle: $data['handle'],
            description: $data['description'],
            variants: array_map(
                fn (array $variant) => ProductVariantDto::fromArray($variant),
                $data['variants']['edges']
            ),
            images: array_map(
                fn (array $image) => ProductImageDto::fromArray($image),
                $data['media']['edges']
            )
        );
    }
}
