<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enums\MediaContentType;

final class ProductImageDto
{
    public function __construct(
        public string $id,
        public string $url,
        public string $alt,
        public MediaContentType $mediaContentType,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['image']['url'],
            alt: $data['alt'],
            mediaContentType: MediaContentType::from($data['mediaContentType'])
        );
    }
}
