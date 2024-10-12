<?php

namespace App\Exceptions\Cart;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ActivePendingCartException extends BadRequestHttpException
{
    public function __construct($message = 'You already have an active cart. Please complete or cancel it before creating a new one.')
    {
        parent::__construct($message);
    }
}
