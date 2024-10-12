<?php

namespace App\Http\Resources\Shopify;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopifyProductImageResource extends JsonResource
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
            'url' => $this->url,
            'alt' => $this->alt,
        ];
    }
}
