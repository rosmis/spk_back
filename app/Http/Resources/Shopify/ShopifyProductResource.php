<?php

namespace App\Http\Resources\Shopify;

use App\Dto\ProductDto;
use App\Http\Resources\Product\ProductVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopifyProductResource extends JsonResource
{
    /**
     * @property ProductDto $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'variants' => ProductVariantResource::collection($this->resource->variants),
            'images' => ShopifyProductImageResource::collection($this->resource->images)
        ];
    }
}
