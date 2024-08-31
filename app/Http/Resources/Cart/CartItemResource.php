<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'variant' => $this->whenLoaded(
                'productVariant',
                fn () => $this->productVariant->only('id', 'title', 'price')
            ),
            'quantity' => $this->quantity,
        ];
    }
}
