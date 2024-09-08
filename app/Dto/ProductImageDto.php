<?php

declare(strict_types=1);

namespace App\Dto;

final class ProductImageDto
{
    public function __construct(
        public string $id,
        public string $url,
        public ?string $alt,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['node']['id'],
            url: $data['node']['image']['url'],
            alt: $data['node']['image']['altText'] ?? null,
        );
    }
}
