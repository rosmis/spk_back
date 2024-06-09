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
    // TODO - Error handling
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

    /**
     * @return Collection<int,ProductDto>
     */
    public function fetchProduts(): Collection
    {
        $queryString = <<<'QUERY'
        query getProductsAndVariants {
          products(first: 9) {
            edges {
              cursor
              node {
                id
                title
                description
                handle
                variants(first: 3) {
                  edges {
                    cursor
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
                media(first: 3) {
                  edges {
                    node {
                      mediaContentType
                      alt
                      ...mediaFieldsByType
                    }
                  }
                }
              }
            }
          }
        }

        fragment mediaFieldsByType on Media {
          ...on MediaImage {
            id
            image {
              url
              altText
            }
          }
        }
        QUERY;

        $response = $this->storefrontClient->query(data: $queryString);

        return Collection::make(
            array_map(
                fn (array $product) => ProductDto::fromArray($product['node']),
                $response->getDecodedBody()['data']['products']['edges']
            )
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

            media(first: 3) {
              edges {
                node {
                  mediaContentType
                  alt
                  ...mediaFieldsByType
                }
              }
            }
          }
        }

        fragment mediaFieldsByType on Media {
          ...on MediaImage {
            id
            image {
              url
              altText
            }
          }
        }
        QUERY;

        $response = $this->storefrontClient->query(data: $queryString);

        return ProductDto::fromArray($response->getDecodedBody()['data']['productByHandle']);
    }
}
