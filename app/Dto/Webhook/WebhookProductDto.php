<?php

declare(strict_types=1);

namespace App\Dto\Webhook;

use App\Dto\ProductImageDto;
use App\Dto\ProductVariantDto;

final class WebhookProductDto
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
            id: $data['admin_graphql_api_id'],
            title: $data['title'],
            handle: $data['handle'],
            description: $data['body_html'],
            variants: array_map(
                fn (array $variant) => WebhookProductVariantDto::fromArray($variant),
                $data['variants']
            ),
            images: array_map(
                fn (array $image) => WebhookProductImageDto::fromArray($image),
                $data['images']
            )
        );
    }
}
