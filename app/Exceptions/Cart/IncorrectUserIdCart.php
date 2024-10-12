<?php

namespace App\Exceptions\Cart;

use Illuminate\Auth\Access\AuthorizationException;

class IncorrectUserIdCart extends AuthorizationException
{
    public function __construct($message = 'The cart\'s user_id does not match the authenticated user.')
    {
        parent::__construct($message);
    }
}
