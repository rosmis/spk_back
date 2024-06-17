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
            id: $data['admin_graphql_api_id'],
            url: $data['src'],
            alt: $data['alt'] ?? null,
        );
    }
}
