<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ShopifyInterface;
use App\Dto\ShopifyConfigDto;
use Illuminate\Support\Collection;
use Shopify\Clients\Storefront;
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
        $storefrontClient = new Storefront(
            $this->config->hostName,
            $this->config->apiKey
        );
        // Use `query` method and pass your query as `data`
        $queryString = <<<'QUERY'
            {
                products (first: 10) {
                    edges {
                        node {
                            id
                            title
                            descriptionHtml
                        }
                    }
                }
            }
        QUERY;

        $response = $storefrontClient->query(data: $queryString);

        dd($response->getDecodedBody());
    }
}
