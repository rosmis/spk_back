<?php

namespace App\Http\Resources\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        if (! $this->resource instanceof Cart) {
            return null;
        }

        return [
            'id' => $this->id,
            'items' => $this->whenLoaded(
                'cartItems',
                CartItemResource::collection($this->cartItems)
            ),
            'status' => $this->status,
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse($request, $response): void
    {
        if (is_null($this->resource)) {
            $response->setData(null);
        }
    }
}
