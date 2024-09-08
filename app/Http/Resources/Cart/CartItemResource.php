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
            'variant' => $this->whenLoaded(
                'productVariant',
                fn () => $this->resource->productVariant->only('id', 'title', 'price')
            ),
            'quantity' => $this->resource->quantity,
        ];
    }
}
