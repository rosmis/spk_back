<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ShopifyInterface;
use App\Dto\ProductDto;
use App\Dto\ShopifyConfigDto;
use Illuminate\Support\Collection;
use Shopify\Clients\Storefront;
use Shopify\Context;

readonly class ShopifyService implements ShopifyInterface
{
    private Storefront $storefrontClient;

    public function __construct(
        public ShopifyConfigDto $config
    ) {
        Context::initialize(...$this->config->toArray());

        $this->storefrontClient = new Storefront(
            $this->config->hostName,
            $this->config->apiKey
        );
    }

    public function fetchProduts(): Collection
    {
        $queryString = <<<'QUERY'
        {
          productByHandle(handle: "soap") {
            id
            title
            description
            variants(first: 3) {
              edges {
                node {
                  id
                  title
                  quantityAvailable
                  price {
                    amount
                    currencyCode
                  }
                }
              }
            }
          }
        }
        QUERY;

        $response = $this->storefrontClient->query(data: $queryString);

        return Collection::make(
            $response->getDecodedBody()['data']
        );
    }

    public function fetchProductByHandle(string $handle): ProductDto
    {
        $queryString = <<<QUERY
        {
          productByHandle(handle: "{$handle}") {
            id
            title
            description
            variants(first: 3) {
              edges {
                node {
                  id
                  title
                  quantityAvailable
                  price {
                    amount
                    currencyCode
                  }
                }
              }
            }
          }
        }
        QUERY;

        $response = $this->storefrontClient->query(data: $queryString);

        return ProductDto::fromArray($response->getDecodedBody()['data']['productByHandle']);
    }
}
