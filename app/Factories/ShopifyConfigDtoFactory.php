<?php

namespace App\Factories;

use App\Dto\ShopifyConfigDto;

class ShopifyConfigDtoFactory
{
    public function make(): ShopifyConfigDto
    {
        return ShopifyConfigDto::fromArray(config('shopify.config'));
    }

}