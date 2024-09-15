<?php

namespace App\Exceptions\Cart;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadStatusCartException extends BadRequestHttpException
{
    public function __construct($message = 'Cart cannot be updated because it is not in the correct status.')
    {
        parent::__construct($message);
    }
}
