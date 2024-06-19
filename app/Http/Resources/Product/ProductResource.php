<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'variants' => $this->whenLoaded(
                'variants',
                ProductVariantResource::collection($this->variants)
            ),
            'images' => $this->whenLoaded(
                'images',
                ProductImageResource::collection($this->images)
            ),
        ];
    }
}
