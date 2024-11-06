<?php

namespace App\Exceptions\Cart;

use App\Exceptions\BusinessException;

class OutOfStockCartVariantException extends BusinessException
{
    public function __construct($message = 'The variant is out of stock.')
    {
        parent::__construct($message);
    }
}
