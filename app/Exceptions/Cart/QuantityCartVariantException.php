<?php

namespace App\Exceptions\Cart;

use App\Exceptions\BusinessException;

class QuantityCartVariantException extends BusinessException
{
    public function __construct($message = 'The quantity of the variant is not available.')
    {
        parent::__construct($message);
    }
}
