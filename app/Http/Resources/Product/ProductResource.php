<?php

namespace App\Http\Resources\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * @property Product $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'type' => $this->resource->type->value,
            'description' => $this->resource->description,
            'variants' => $this->whenLoaded(
                'variants',
                ProductVariantResource::collection($this->resource->variants)
            ),
            'images' => $this->whenLoaded(
                'images',
                ProductImageResource::collection($this->resource->images)
            ),
        ];
    }
}
