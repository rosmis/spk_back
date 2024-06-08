<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ShopifyInterface;
use App\Dto\ShopifyConfigDto;
use Illuminate\Support\Collection;
use Shopify\Context;

readonly class ShopifyService implements ShopifyInterface
{
    public function __construct(
        public ShopifyConfigDto $config
    ) {
        Context::initialize(...$this->config->toArray());
    }

    public function fetchProduts(): Collection
    {
        dd('fetchProduts');
    }
}
