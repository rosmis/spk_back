<?php

namespace App\Exceptions\Cart;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuantityCartVariantException extends BadRequestHttpException
{
    public function __construct($message = 'The quantity of the variant is not available.')
    {
        parent::__construct($message);
    }
}
