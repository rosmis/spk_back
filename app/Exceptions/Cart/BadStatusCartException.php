<?php

namespace App\Exceptions\Cart;

use Illuminate\Auth\Access\AuthorizationException;

class BadStatusCartException extends AuthorizationException
{
    public function __construct($message = 'Cart cannot be updated because it is not in the correct status.')
    {
        parent::__construct($message);
    }
}
