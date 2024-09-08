<?php

namespace App\Factories;

use App\Dto\ShopifyConfigDto;
use Shopify\Clients\Storefront;
use Shopify\Context;
use Shopify\Exception\MissingArgumentException;

class StoreFrontFactory
{
    private ShopifyConfigDto $configDto;

    public function __construct(
        public ShopifyConfigDtoFactory $configFactory,
    ) {
        $this->configDto = $this->configFactory->make();
    }

    public function make(): Storefront
    {
        try {
            Context::initialize(...$this->configDto->toArray());

            return new Storefront(
                $this->configDto->hostName,
                $this->configDto->apiKey,
            );
        } catch (MissingArgumentException $e) {
            throw $e;
        }
    }

}