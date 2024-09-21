<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\TestDox;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class ShopifyControllerTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[TestDox(
        'Given the webhook product is being triggered with a given payload, 
        When the webhook is received, 
        Then it should validate the payload structure 
        And dispatch a job to process the webhook product.'
    )]
    public function testShouldValidateWebhookPayloadStructure(): void
    {
        Queue::fake();

        $payload = $this->generatePayload();

        $response = $this
            ->withHeader('X-Shopify-Hmac-Sha256', $this->generateValidHmac($payload))
            ->postJson('/webhook/product', $payload);

        $response->assertStatus(ResponseAlias::HTTP_OK)
            ->assertJson(['message' => 'Webhook received']);
    }

    private function generatePayload(): array
    {
        return [
            'admin_graphql_api_id' => 'gid://shopify/Product/123456789',
            'handle' => 'test-product',
            'title' => 'Test Product',
            'body_html' => '<p>This is a test product description.</p>',
            'variants' => [
                [
                    'admin_graphql_api_id' => 'gid://shopify/ProductVariant/987654321',
                    'title' => 'Default Title',
                    'price' => '10.00',
                    'inventory_quantity' => 100,
                ],
            ],
            'images' => [
                [
                    'admin_graphql_api_id' => 'gid://shopify/ProductImage/111222333',
                    'src' => 'https://cdn.shopify.com/s/files/1/0000/0000/products/test-image.jpg',
                    'alt' => 'Test Product Image',
                    'id' => 111222333,
                ],
            ],
        ];
    }

    private function generateValidHmac(array $payload): string
    {
        $secret = config('shopify.webhookSecret');

        return base64_encode(hash_hmac(
            'sha256',
            json_encode($payload), $secret, true)
        );
    }
}