<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;

class ActivePendingCartException extends AuthorizationException
{
    public function __construct($message = 'You already have an active cart. Please complete or cancel it before creating a new one.')
    {
        parent::__construct($message);
    }
}
