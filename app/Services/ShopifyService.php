<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ShopifyInterface;
use App\Dto\Cart\CartItemDto;
use App\Dto\ProductDto;
use App\Factories\StoreFrontFactory;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Shopify\Clients\Storefront;
use Shopify\Exception\MissingArgumentException;

readonly class ShopifyService implements ShopifyInterface
{
    // TODO - Error handling
    private Storefront $storefrontClient;

    public function __construct(
        public StoreFrontFactory $storeFrontFactory
    ) {
        $this->storefrontClient = $this->storeFrontFactory->make();
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
            handle
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

        return ProductDto::fromArray(
            $response->getDecodedBody()['data']['productByHandle']
        );
    }

    /**
     * @param  Collection<int,CartItemDto>  $cartItems
     *
     * @throws MissingArgumentException
     * @throws Exception
     */
    public function generateCartCheckoutUrl(Collection $cartItems, User $user): string
    {
        $lineItemsString = $cartItems->map(
            fn (CartItemDto $cartItem) => $this->toLineItemString($cartItem)
        )->implode(',');

        $queryString = <<<QUERY
            mutation {
              cartCreate(
                input: {
                  lines: [{$lineItemsString}]
                  buyerIdentity: {
                    email: "{$user->email}"
                  }
                  attributes: [
                    {
                      key: "user_id",
                      value: "{$user->id}"
                    }
                  ]
                }
              ) {
                cart {
                  id
                  checkoutUrl
                }
              }
            }
        QUERY;

        try {
            $response = $this->storefrontClient->query(data: $queryString);

            $checkoutData = $response->getDecodedBody()['data'];

            return $checkoutData['cartCreate']['cart']['checkoutUrl'];
        } catch (Exception $e) {
            throw new Exception('Failed to create checkout: ' . $e->getMessage());
        }
    }

    private function toLineItemString(CartItemDto $cartItemDto): string
    {
        return "{merchandiseId: \"{$cartItemDto->shopifyGid}\", quantity: {$cartItemDto->quantity}}";
    }
}