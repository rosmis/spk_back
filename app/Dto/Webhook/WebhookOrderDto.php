<?php

declare(strict_types=1);

namespace App\Dto\Webhook;

final class WebhookOrderDto
{
    public function __construct(
        public string $id,
        public string $email,
        public string $reference,
        public int $user_id
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['admin_graphql_api_id'],
            email: $data['contact_email'],
            user_id: (int) $data['note_attributes'][0]['value'],
            reference: $data['reference']
        );
    }
}
