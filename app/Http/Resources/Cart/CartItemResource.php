<?php

namespace App\Http\Resources\Cart;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * @property CartItem $resource
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'imageUrl' => $this->resource->image_url,
            'variant' => $this->whenLoaded(
                'productVariant',
                fn () => [
                    'id' => $this->resource->productVariant->id,
                    'title' => $this->resource->productVariant->title,
                    'price' => $this->resource->productVariant->price,
                ]
            ),
            'quantity' => $this->resource->quantity,
        ];
    }
}
