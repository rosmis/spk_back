<?php

namespace App\Http\Resources\Cart;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * @property Cart $resource
     */
    public function toArray(Request $request): ?array
    {
        if (! $this->resource instanceof Cart) {
            return null;
        }

        return [
            'id' => $this->resource->id,
            'items' => $this->whenLoaded(
                'cartItems',
                CartItemResource::collection($this->resource->cartItems)
            ),
            'status' => $this->resource->status,
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
