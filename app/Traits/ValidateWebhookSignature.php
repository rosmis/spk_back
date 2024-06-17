<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\Request;

trait ValidateWebhookSignature
{
    public function validateWebhookSignature(Request $request): bool
    {
        $webhookSecret = config('shopify.webhookSecret');
        $hmacHeader = $request->header('X-Shopify-Hmac-Sha256');

        if (! $hmacHeader) {
            return false;
        }

        $calculatedHmac = base64_encode(
            hash_hmac('sha256', $request->getContent(), $webhookSecret, true)
        );

        return hash_equals($hmacHeader, $calculatedHmac);
    }
}