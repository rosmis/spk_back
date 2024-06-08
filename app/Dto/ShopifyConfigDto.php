<?php

declare(strict_types=1);

namespace App\Dto;

use Shopify\Auth\FileSessionStorage;

final class ShopifyConfigDto
{
    public function __construct(
        public string $apiKey,
        public string $apiSecretKey,
        public string $scopes,
        public string $hostName,
        public FileSessionStorage $sessionStorage,
        public string $apiVersion,
        public bool $isEmbeddedApp,
        public bool $isPrivateApp,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            apiKey: $data['apiKey'],
            apiSecretKey: $data['apiSecretKey'],
            scopes: $data['scopes'],
            hostName: $data['hostName'],
            sessionStorage: $data['sessionStorage'],
            apiVersion: $data['apiVersion'],
            isEmbeddedApp: $data['isEmbeddedApp'],
            isPrivateApp: $data['isPrivateApp'],
        );
    }

    public function toArray(): array
    {
        return [
            'apiKey' => $this->apiKey,
            'apiSecretKey' => $this->apiSecretKey,
            'scopes' => $this->scopes,
            'hostName' => $this->hostName,
            'sessionStorage' => $this->sessionStorage,
            'apiVersion' => $this->apiVersion,
            'isEmbeddedApp' => $this->isEmbeddedApp,
            'isPrivateApp' => $this->isPrivateApp,
        ];
    }
}
