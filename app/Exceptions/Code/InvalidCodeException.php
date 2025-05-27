<?php

namespace App\Exceptions\Code;

use App\Exceptions\BusinessException;

class InvalidCodeException extends BusinessException
{
    public function __construct($message = 'Invalid code provided')
    {
        parent::__construct($message);
    }
}
