<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class InvalidWebhookSignatureException extends AuthorizationException
{
    public function __construct($message = 'Invalid webhook signature')
    {
        parent::__construct($message);
    }
}
